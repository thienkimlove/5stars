<div id="fb-root"></div>
<script type="text/javascript">// <![CDATA[
  window.fbAsyncInit = function(){
    FB.init({
      appId      : '<?php echo Configure::read('fbId') ?>', // App ID from the App Dashboard
      channelUrl : '<?php echo Router::url('/channel.php', true) ?>', // Channel File for x-domain communication
      status  : true,
      cookie  : true,
      xfbml   : true
    });
    
    $('#fb-root').trigger('facebook:init');

  };
  (function(){
    var e = document.createElement('script');
    e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
    e.async = true;
    document.getElementById('fb-root').appendChild(e);
  }());
// ]]></script>