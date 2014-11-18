<script type="text/javascript">
function rePost() {
    window.location = "<?php echo $this->Session->read('Auth.CurrentUrl'); ?>";
}
function backToGame() {
    window.location = '<?php echo $gameUrl; ?>';
}
</script>
<div class="control-group">

    <p id="switch" class="switch">
        <button onclick="rePost()" class="btn btn-block btn-success">Re-Post</button>
        <button onclick="backToGame()" class="btn btn-block btn-primary">Back To Game</button>
    </p>
    <p>
       <?php echo $message; ?>
    </p>

</div>


