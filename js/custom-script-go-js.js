$(document).ready(function () {
    var saveButton = $('#save');
    saveButton.on('click', function () {
        var save = true;

        var modal = $('#myModal');
        var modalHeader = $('#myModal .modal-title');
        var modalBody = $('#myModal .modal-body');

        var dataArr = myDiagram.model.nodeDataArray;
        for (var i = dataArr.length - 1; i >= 0; i--) {
          if (dataArr[i].category == 'Start') {
            var check = checkNodeLinks('Start', dataArr[i].key);
            if (check !== true) {
                save = false;
                modalHeader.text('Error')
                modalBody.text(check)
                modal.modal('show');
            }

            
        } 
        if (dataArr[i].category == 'Circle') {
            var check = checkNodeLinks('Circle', dataArr[i].key);
            if (check !== true) {
                save = false;
                modalHeader.text('Error')
                modalBody.text(check)
                modal.modal('show');
            }
        } 
    }

    if (save) {
        var diagramArray = myDiagram.model.toJson();
        var project = $('.project').text();
        $.ajax({
            type: "POST",
            url: 'add_diagram_to_db.php',
            data: {
                data : diagramArray,
                project: project
            },
            error: function(error){
                console.log(error);
            },
            success: function(data){
                var modal = $('#myModal');
                var modalHeader = $('#myModal .modal-title');
                var modalBody = $('#myModal .modal-body');

                modalHeader.text(data.status)
                modalBody.text(data.message)
                modal.modal('show');
            }
        });
    }
});

    function checkNodeLinks(Figure, key){
        var dataArr = myDiagram.model.linkDataArray;
        counterFrom = 0;
        counterTo = 0;
        if (Figure == 'Start') {
            for (var i = dataArr.length - 1; i >= 0; i--) {

                if (dataArr[i].from == key) {
                    counterFrom++;
                }
            }
            switch (counterFrom) {
              case 0:
              return 'All Start figures should lead to a Circle';
              break;
              case 1:
              return true;
              break;
              default:
              return 'All Start figures should have only one line to the Circle';
          }
      } 

      if (Figure == 'Circle') {
          for (var i = dataArr.length - 1; i >= 0; i--) {
            if (dataArr[i].from == key ) {
                counterFrom++;
            } else if (dataArr[i].to == key) {
                counterTo++;
            }
        }
        if (counterFrom == 0 || counterTo == 0) {
          return 'All Circle figures should be connected from input and output!';
      } else {
         return true;
     }

 }
}

function init() {
        if (window.goSamples) goSamples();  // init for these samples -- you don't need to call this
        var $ = go.GraphObject.make;  // for conciseness in defining templates
        myDiagram =
            $(go.Diagram, "diagramDiv",  // must name or refer to the DIV HTML element
            {
                initialContentAlignment: go.Spot.Center,
                    allowDrop: true,  // must be true to accept drops from the Palette
                    "LinkDrawn": showLinkLabel,  // this DiagramEvent listener is defined below
                    "LinkRelinked": showLinkLabel,
                    "animationManager.duration": 800, // slightly longer than default (600ms) animation
                    "undoManager.isEnabled": true  // enable undo & redo
                });


        // helper definitions for node templates
        function nodeStyle() {
            return [
            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
            {
                locationSpot: go.Spot.Center,
                isShadowed: true,
                shadowColor: "#888",
                    // handle mouse enter/leave events to show/hide the ports
                    mouseEnter: function (e, obj) {
                        showPorts(obj.part, true);
                    },
                    mouseLeave: function (e, obj) {
                        showPorts(obj.part, false);
                    }
                }
                ];
            }



        // Define a function for creating a "port" that is normally transparent.
        // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
        // and where the port is positioned on the node, and the boolean "output" and "input" arguments
        // control whether the user can draw links from or to the port.
        function makePort(name, spot, output, input) {
            // the port is basically just a small circle that has a white stroke when it is made visible
            return $(go.Shape, "Circle",
            {
                fill: "transparent",
                    stroke: null,  // this is changed to "white" in the showPorts function
                    desiredSize: new go.Size(8, 8),
                    alignment: spot, alignmentFocus: spot,  // align the port on the main Shape
                    portId: name,  // declare this object to be a "port"
                    fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
                    fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
                    cursor: "pointer"  // show a different cursor to indicate potential link point
                });
        }

        // define the Node templates for regular nodes

        var lightText = 'whitesmoke';



       /* myDiagram.nodeTemplateMap.add("LoopLimit",  // the default category
            $(go.Node, "Spot", nodeStyle(),
                // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                $(go.Panel, "Auto",
                    $(go.Shape, "LoopLimit",
                        {fill: "#00A9C9", stroke: null},
                        new go.Binding("figure", "figure")),
                    $(go.TextBlock,
                    {
                        font: "bold 11pt Helvetica, Arial, sans-serif",
                        stroke: '#00A9C9',
                        margin: 8,
                        maxSize: new go.Size(160, 50),
                        wrap: go.TextBlock.WrapFit,
                        editable: true
                    },
                    new go.Binding("text").makeTwoWay())
                    ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, false, true),
                makePort("L", go.Spot.Left, true, true),
                makePort("R", go.Spot.Right, true, true),
                makePort("B", go.Spot.Bottom, true, false)
                ));*/

                myDiagram.nodeTemplateMap.add("Start",
                    $(go.Node, "Spot", nodeStyle(),
                        $(go.Panel, "Auto",
                            $(go.Shape, "Triangle",
                                {minSize: new go.Size(40, 40),maxSize: new go.Size(50, 40), fill: "#79C900", stroke: null}),
                            $(go.TextBlock, "End",
                            {
                                font: "bold 11pt Helvetica, Arial, sans-serif",
                                stroke: '#79C900',
                                editable: true
                            },
                            new go.Binding("text").makeTwoWay()
                            )
                            ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),

                ));

                myDiagram.nodeTemplateMap.add("End",
                    $(go.Node, "Spot", nodeStyle(),
                        $(go.Panel, "Auto",
                            $(go.Shape, "Circle",
                                {minSize: new go.Size(40, 40), maxSize: new go.Size(40, 40), fill: "#DC3C00", stroke: null}),
                            $(go.TextBlock, "End",
                                {font: "bold 11pt Helvetica, Arial, sans-serif", stroke: '#DC3C00', editable: true,},
                                new go.Binding("text").makeTwoWay())
                            ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("B", go.Spot.Bottom, false, true)
                ));


                myDiagram.nodeTemplateMap.add("Circle",
                    $(go.Node, "Spot", nodeStyle(),
                        $(go.Panel, "Auto",
                            $(go.Shape, "Circle",
                                {minSize: new go.Size(50, 50),maxSize: new go.Size(50, 50), fill: "#00A9C9", stroke: null}),
                            $(go.TextBlock, "End",
                                {font: "bold 11pt Helvetica, Arial, sans-serif", stroke: "#00A9C9", editable: true,},
                                new go.Binding("text").makeTwoWay())
                            ),
                // three named ports, one on each side except the bottom, all input only:
                makePort("T", go.Spot.Top, true, false),
                makePort("B", go.Spot.Bottom, false, true)
                ));

                myDiagram.nodeTemplateMap.add("Rectangle", 
                    $(go.Node, "Spot", nodeStyle(),
                // the main object is a Panel that surrounds a TextBlock with a rectangular Shape
                $(go.Panel, "Auto",
                    $(go.Shape, "Rectangle",
                        {fill: "#00A9C9", stroke: null, maxSize: new go.Size(50, 35), minSize: new go.Size(50, 35)},
                        new go.Binding("figure", "figure")),
                    $(go.TextBlock,
                    {
                        font: "bold 11pt Helvetica, Arial, sans-serif",
                        stroke: '#00A9C9',
                        margin: 8,
                        
                        wrap: go.TextBlock.WrapFit,
                        editable: true
                    },
                    new go.Binding("text").makeTwoWay())
                    ),
                // four named ports, one on each side:
                makePort("T", go.Spot.Top, true, false),
                makePort("B", go.Spot.Bottom, false, true)
                ));

               /* myDiagram.nodeTemplateMap.add("Comment",
                    $(go.Node, "Auto", nodeStyle(),
                        $(go.Shape, "File",
                            {fill: "#EFFAB4", stroke: null}),
                        $(go.TextBlock,
                        {
                            margin: 5,
                            maxSize: new go.Size(200, NaN),
                            wrap: go.TextBlock.WrapFit,
                            textAlign: "center",
                            editable: true,
                            font: "bold 12pt Helvetica, Arial, sans-serif",
                            stroke: '#454545'
                        },
                        new go.Binding("text").makeTwoWay()),
                        makePort("T", go.Spot.Top, false, true),
                        makePort("L", go.Spot.Left, true, true),
                        makePort("R", go.Spot.Right, true, true),
                        makePort("B", go.Spot.Bottom, true, true)
                // no ports, because no links are allowed to connect with a comment
                ));*/


        // replace the default Link template in the linkTemplateMap
        myDiagram.linkTemplate =
            $(go.Link,  // the whole link panel
            {
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpOver,
                corner: 5, toShortLength: 4,
                relinkableFrom: true,
                relinkableTo: true,
                reshapable: true,
                resegmentable: true,
                    // mouse-overs subtly highlight links:
                    mouseEnter: function (e, link) {
                        link.findObject("HIGHLIGHT").stroke = "rgba(30,144,255,0.2)";
                    },
                    mouseLeave: function (e, link) {
                        link.findObject("HIGHLIGHT").stroke = "transparent";
                    }
                },
                new go.Binding("points").makeTwoWay(),
                $(go.Shape,  // the highlight shape, normally transparent
                    {isPanelMain: true, strokeWidth: 8, stroke: "transparent", name: "HIGHLIGHT"}),
                $(go.Shape,  // the link path shape
                    {isPanelMain: true, stroke: "gray", strokeWidth: 2}),
                $(go.Shape,  // the arrowhead
                    {toArrow: "standard", stroke: null, fill: "gray"}),
                $(go.Panel, "Auto",  // the link label, normally not visible
                    {visible: false, name: "LABEL", segmentIndex: 2, segmentFraction: 0.5},
                    new go.Binding("visible", "visible").makeTwoWay(),
                    $(go.Shape, "RoundedRectangle",  // the label shape
                        {fill: "#F8F8F8", stroke: null}),
                    $(go.TextBlock, "Yes",  // the label
                    {
                        textAlign: "center",
                        font: "10pt helvetica, arial, sans-serif",
                        stroke: "#333333",
                        editable: true
                    },
                    new go.Binding("text").makeTwoWay())
                    )
                );

        // Make link labels visible if coming out of a "conditional" node.
        // This listener is called by the "LinkDrawn" and "LinkRelinked" DiagramEvents.
        function showLinkLabel(e) {
            var label = e.subject.findObject("LABEL");
            if (label !== null) label.visible = (e.subject.fromNode.data.figure === "Diamond");
        }

        // temporary links used by LinkingTool and RelinkingTool are also orthogonal:
        myDiagram.toolManager.linkingTool.temporaryLink.routing = go.Link.Orthogonal;
        myDiagram.toolManager.relinkingTool.temporaryLink.routing = go.Link.Orthogonal;

        load();  // load an initial diagram from some JSON text

        // initialize the Palette that is on the left side of the page
        myPalette =
            $(go.Palette, "figurePalette",  // must name or refer to the DIV HTML element
            {
                    "animationManager.duration": 800, // slightly longer than default (600ms) animation
                    nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
                    model: new go.GraphLinksModel([  // specify the contents of the Palette
                        {category: "End", text: "End"},
                        // {category: "Rectangle",text: "Step"},
                        {category: "Circle",text: "Step"},
                        // {category: "LoopLimit",text: "Step"},
                        {category: "Start", text: "Start"},
                        // {category: "Comment", text: "Comment"}
                        ])
                });

        // The following code overrides GoJS focus to stop the browser from scrolling
        // the page when either the Diagram or Palette are clicked or dragged onto.

        function customFocus() {
            var x = window.scrollX || window.pageXOffset;
            var y = window.scrollY || window.pageYOffset;
            go.Diagram.prototype.doFocus.call(this);
            window.scrollTo(x, y);
        }


        myDiagram.doFocus = customFocus;
        myPalette.doFocus = customFocus;



///////////////////////////////////
//MY FUNCTIONS
///////////////////////////////////
function figures_arrangement(){
    var dataArr = myDiagram.model.nodeDataArray;
    for (var i = dataArr.length - 1; i >= 0; i--) {
      if (dataArr[i].category == 'Start') {
                var node = myDiagram.findNodeForData(dataArr[i]);   // find the corresponding Node
                var p = node.location.copy();  // make a copy of the location, a Point
                p.y = myDiagram.viewportBounds.bottom-40;
                node.location = p;
            } 

            if (dataArr[i].category == 'End') {
                var node = myDiagram.findNodeForData(dataArr[i]);   // find the corresponding Node
                var p = node.location.copy();  // make a copy of the location, a Point
                p.y = myDiagram.viewportBounds.top+40;
                p.x = myDiagram.viewportBounds.centerX;
                node.location = p;
            } 
        }
    } 



        //ON ADDED NEW OBJECT
        myDiagram.addDiagramListener("ExternalObjectsDropped", function (ev) {
           var dataArr = myDiagram.model.nodeDataArray;

           if (ev.subject.Ca.key.Wd.category == 'Start') {
               var data = dataArr[dataArr.length-1];  
               var node = myDiagram.findNodeForData(data);   
               var p = node.location.copy(); 
               p.y = myDiagram.viewportBounds.bottom-40;
               node.location = p;
           }

           if (ev.subject.Ca.key.Wd.category == 'End') {
            deleteIT = false;

            for (var i = dataArr.length - 1; i >= 0; i--) {
                if (dataArr[i].category == 'End') {
                    if (weHaveEnd) {
                        deleteIT = true;
                    }
                    var weHaveEnd = true;
                } 
            }
            var data = dataArr[dataArr.length-1];  
            var node = myDiagram.findNodeForData(data);

            if (deleteIT) {
                myDiagram.model.removeNodeData(data);
            } else {
               var p = node.location.copy(); 
               p.y = myDiagram.viewportBounds.top+40;
               p.x = myDiagram.viewportBounds.centerX;

               node.location = p;
           }

       }

   });

        //ON RESIZE FUNCTION
        myDiagram.addDiagramListener("ViewportBoundsChanged", function () { figures_arrangement() });
        //ON ADDED NEW OBJECT
        myDiagram.addDiagramListener("SelectionMoved", function () {figures_arrangement() });

//////////////////////////////////


    } // end init

// Make all ports on a node visible when the mouse is over the node
function showPorts(node, show) {
    var diagram = node.diagram;
    if (!diagram || diagram.isReadOnly || !diagram.allowLink) return;
    node.ports.each(function (port) {
        port.stroke = (show ? "white" : null);
    });
}

function load() {
    var project = $('.project').text();
    $.ajax({
        type: "POST",
        url: 'load_project.php',
        data: {
            data : project
        },
        error: function(error){
            console.log(error);
        },
        success: function(data){
            myDiagram.model = go.Model.fromJson(data);
        }
    });

}

init();
});