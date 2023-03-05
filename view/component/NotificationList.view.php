
<?php if (isset($notObjs)): ?>
<div class="notificationList" style="display: flex; flex-direction:column; width: 100%">
<?php foreach ($notObjs as $not): ?>
    <div class="notificationItemBox" data-id="<?= $not->id ?>" data-is-read="<?= $not->isRead ?>">
        <a href="<?= URL\URLGenerator::decodeJSONStruct($not->linkUrlInfos) ?>">
            <div class="notificationIconBox">
                <img src="<?= URL\URLGenerator::generateFileURL($not->iconFilePath) ?>" height="50" />
            </div>
            <div class="notificationTextBox">
                <h1><?= hsc($not->title) ?></h1>
                <p><?= hsc($not->description ?? '') ?></p>
                <span class="notificationDateTime"><?= hsc(date_create($not->dateTime)->format('d/m/Y H:i:s')) ?></span>
            </div>
            <div class="notificationReadStatusButton">
                <label style="display:block;">
                    <input type="checkbox" <?= $not->isRead ? "" : "checked" ?> />
                    <span class="notificationSetReadStatusCircle"></span>
                </label>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>