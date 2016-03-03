<h1>
  <span class="head-title"><?php echo $this->Html->link('収支管理', '/'); ?></span>
  <?php if ($userData) { ?>
    <div class="head-msg fr">
      <span class="head-welcome">ようこそ</span>
      <span class="head-handlename"><?php echo $this->Html->link(($userData['handlename'])? $userData['handlename']: 'ログインユーザ', '/users/'); ?></span>
      <span class="head-welcome">さん</span>
    </div>
  <?php } ?>
</h1>