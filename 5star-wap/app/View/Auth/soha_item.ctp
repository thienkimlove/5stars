
<div class="users form">    
    <form class="content" method="POST" action="<?php echo $this->Html->url(array('controller' => 'auth', 'action' => 'choose')) ?>">
        <h2 class="heading">Chọn số kim cương cần mua</h2>
        <?php foreach ($items as $item) : ?>
        <p class="insert-birth"> 
            <input value="<?php echo $item['Item']['id'] ?>" type="radio" name="data[id]"> Mua <?php echo ($item['Item']['amount']/100) ?> kim cương ( ~ <?php echo $item['Item']['price'] ?> SCoin) 
        </p>
        <?php endforeach; ?>        
        <p class="luuy" style="color:black">Tỷ lệ :100.000 VND = 100 SCoin</p>
        <p class="luuy">Lưu ý : X2 giá trị kim cương nhận được cho lần nạp đầu tiên.</p>
        <p class="btn-submit"><input type="submit" name="Xác nhận" value="Mua kim cương"/></p>
        <p class="btn-submit"><a href="mfzg://main:80">Quay về game</a></p>       
    </form>
</div>