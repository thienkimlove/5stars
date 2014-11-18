<script type="text/javascript">
    function backToGame() {
        window.location = '<?php echo $gameUrl; ?>';
    }
    function invite() {
        FB.ui({method: 'apprequests',
            title: '<?php echo $content['title'] ?>',
            message: '<?php echo $content['content'] ?>',
            }, function(){});
    }
</script>

<?php echo $this->element('facebook'); ?>

<div class="control-group">

    <p id="switch" class="switch">        
        <button onclick="invite()" class="btn btn-block btn-success">Invite Friends</button>
        <button onclick="backToGame()" class="btn btn-block btn-primary">Back To Game</button>
    </p>
    <?php if (!empty($message)) : ?>
        <p>
            <?php echo $message; ?>
        </p>
        <?php endif; ?>

</div>