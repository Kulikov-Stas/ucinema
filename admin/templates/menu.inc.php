<div id="navigation-menu">
	<ul>
		<li><a href="?mode=razdel" class="<?php if ($mode == 'razdel') echo 'active';?>">�������</a>
		<li><a href="?mode=menu" class="<?php if ($mode == 'menu') echo 'active';?>">����</a>
		<li><a href="?mode=config" class="<?php if ($mode == 'config') echo 'active';?>">������������</a>
		<li><a href="?mode=setup" class="<?php if ($mode == 'setup') echo 'active';?>">���������</a>
		<li><a href="?mode=spam"class="<?php if ($mode == 'spam') echo 'active';?>">��������</a>
		<li><a href="?mode=parser" class="<?php if ($mode == 'parser') echo 'active';?>">������</a>
	</ul>
    <form action="http://today.od.ua/admin/grabbers/index.php" method="GET">
    <br />
    <button type="submit" style="cursor:pointer">����������������</button>
    <input type="hidden" name="mode" value="kinotheater" />
    <input type="hidden" name="module" value="ucinema" />
    </form>
</div>
