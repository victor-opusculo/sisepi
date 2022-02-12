<?php
function currQS()
{
	return URL\QueryString::getQueryStringForHtmlExcept("pageNum");
}

function addQSpagNum($pagNum)
{
	return URL\QueryString::formatNew("pageNum", $pagNum, true);
}
?>

<?php if (ceil($totalItems / $numResultsOnPage) > 0): ?>
<ul class="pagination">
	<?php if ($pageNum > 1): ?>
	<li class="prev"><a href="?<?php echo currQS() . addQSpagNum($pageNum - 1); ?>">Anterior</a></li>
	<?php endif; ?>

	<?php if ($pageNum > 3): ?>
	<li class="start"><a href="?<?php echo currQS() . addQSpagNum(1); ?>">1</a></li>
	<li class="dots">...</li>
	<?php endif; ?>

	<?php if ($pageNum-2 > 0): ?><li class="pageNum"><a href="?<?php echo currQS() . addQSpagNum($pageNum-2); ?>"><?php echo $pageNum-2 ?></a></li><?php endif; ?>
	<?php if ($pageNum-1 > 0): ?><li class="pageNum"><a href="?<?php echo currQS() . addQSpagNum($pageNum-1); ?>"><?php echo $pageNum-1 ?></a></li><?php endif; ?>

	<li class="currentpageNum"><a href="?<?php echo currQS() . addQSpagNum($pageNum); ?>"><?php echo $pageNum; ?></a></li>

	<?php if ($pageNum+1 < ceil($totalItems / $numResultsOnPage)+1): ?><li class="pageNum"><a href="?<?php echo currQS() . addQSpagNum($pageNum+1); ?>"><?php echo $pageNum+1 ?></a></li><?php endif; ?>
	<?php if ($pageNum+2 < ceil($totalItems / $numResultsOnPage)+1): ?><li class="pageNum"><a href="?<?php echo currQS() . addQSpagNum($pageNum+2); ?>"><?php echo $pageNum+2 ?></a></li><?php endif; ?>

	<?php if ($pageNum < ceil($totalItems / $numResultsOnPage)-2): ?>
	<li class="dots">...</li>
	<li class="end"><a href="?<?php echo currQS() . addQSpagNum(ceil($totalItems / $numResultsOnPage)); ?>"><?php echo ceil($totalItems / $numResultsOnPage); ?></a></li>
	<?php endif; ?>

	<?php if ($pageNum < ceil($totalItems / $numResultsOnPage)): ?>
	<li class="next"><a href="?<?php echo currQS() . addQSpagNum($pageNum+1); ?>">Próxima</a></li>
	<?php endif; ?>
</ul>
<?php endif; ?>