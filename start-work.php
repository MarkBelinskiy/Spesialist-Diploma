<?php 
if (isset($_GET['project'])) {
  $project = $_GET['project'];
} else {
  header('Location: dashboard.php');
  die();
}
require_once 'header.php';
?>
<div class="project"><?php echo $project ?></div>
<div class="diagram-container">
  <div style="width:100%; white-space:nowrap;">
    <span style="display: inline-block; vertical-align: top; width:10vw">
      <div id="figurePalette" style="border: solid 1px black; height: 99vh"></div>
    </span>
    <span style="display: inline-block; vertical-align: top; width: 89.5vw;">
      <div id="diagramDiv" style="border: solid 1px black; height: 99vh"></div>
      <div class="menu">
        <a href="dashboard.php" class="btn btn-success back-button">BACK</a>
      <button id="save" class="btn btn-success save-button">SAVE</button>
      </div>
    </span>
  </div>
</div>

<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php require_once 'footer.php'; ?>