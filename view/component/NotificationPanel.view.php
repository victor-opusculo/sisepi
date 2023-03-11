<script>    

    SisEpi.Notifications.setNotificationReadState = function()
    {
        let itemBox = this.parentNode.parentNode.parentNode.parentNode;
        itemBox.setAttribute('data-is-read', this.checked ? '0' : '1');

        SisEpi.Notifications.sendReadStatusChange(itemBox.getAttribute('data-id'), this.checked ? 0 : 1);
    };

    SisEpi.Notifications.updateUnreadNotificationCountLabel = function(count)
    {
        document.getElementById('notificationPanelButton').setAttribute('data-notifications', count);
        document.querySelectorAll('.labelUnreadNotificationsCount').forEach( el => el.innerText = (count <= 0 ? 'Todas lidas' : count == 1 ? '1 não lida ' : count + ' não lidas') ); 
    };

    SisEpi.Notifications.addNotificationItem = function(data, parent)
    {
        const div = document.createElement('div');
        div.className = "notificationItemBox";
        div.setAttribute('data-id', data.id);
        div.setAttribute('data-is-read', data.isRead);

        const innerHtml = `
        <a href="${data.linkUrlInfos}">
            <div class="notificationIconBox">
                <img src="${SisEpi.Url.fileBaseUrl + data.iconFilePath}" height="50" />
            </div>
            <div class="notificationTextBox">
                <h1>${SisEpi.Layout.escapeHtml(data.title)}</h1>
                <p>${SisEpi.Layout.escapeHtml(data.description)}</p>
                <span class="notificationDateTime">${SisEpi.Layout.escapeHtml(data.dateTime)}</span>
            </div>
            <div class="notificationReadStatusButton">
                <label style="display:block;">
                    <input 
                        type="checkbox" ${data.isRead ? '' : ' checked '}
                        autocomplete="off" />
                    <span class="notificationSetReadStatusCircle"></span>
                </label>
            </div>
        </a>
        `;
        div.innerHTML = innerHtml;

        let seeMoreLinkDiv = parent.querySelector('.notificationSeeMoreLink');

        parent.insertBefore(div, seeMoreLinkDiv);
    }; 

    SisEpi.Notifications.clearNotifications = function(parent)
    {
    
        while (parent.firstChild && parent.firstChild.className !== 'notificationSeeMoreLink') 
            parent.removeChild(parent.firstChild);
    };

    SisEpi.Notifications.setErrorMessage = function(message)
    {
        const panel = document.getElementById('notificationDropdown');
        SisEpi.Notifications.clearNotifications(panel);

        const html = `
        <p style="text-align: center;">Erro ao obter notificações.</p>
        <p>${SisEpi.Layout.escapeHtml(message)}</p>
        `;
        const el = document.createElement('div');
        el.innerHTML = html;

        panel.appendChild(el);
    };

    SisEpi.Notifications.fetchLastNotifications = async function()
    {
        const scriptURL = SisEpi.Url.fileBaseUrl + '/generate/getLastNotifications.php';
        try
        {
            const panel = document.getElementById('notificationDropdown');

            let res = await fetch(scriptURL);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            if (json.data)
                SisEpi.Notifications.clearNotifications(panel);

            if (!isNaN(json.data.unread))
                SisEpi.Notifications.updateUnreadNotificationCountLabel(json.data.unread);
            else
                SisEpi.Notifications.updateUnreadNotificationCountLabel(0);

            if (json.data.lastNotifications)
                json.data.lastNotifications.forEach( notData => SisEpi.Notifications.addNotificationItem(notData, panel) );

            SisEpi.Notifications.setReadStateButtonHandler();
        }
        catch (err)
        {
            SisEpi.Notifications.setErrorMessage(err.message);
        }
    };

    SisEpi.Notifications.sendReadStatusChange = async function(notificationId, newReadState)
    {
        const scriptURL = SisEpi.Url.fileBaseUrl + `/generate/setNotificationRead.php?id=${notificationId}&read=${newReadState}`;

        try
        {
            let res = await fetch(scriptURL);
            let json = await res.json();

            if (json.error)
                throw new Error(json.error);

            SisEpi.Notifications.updateUnreadNotificationCountLabel(json.data.unread);
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    };

    SisEpi.Notifications.setReadStateButtonHandler = function()
    {
        let elements = document.querySelectorAll('.notificationItemBox');
        elements.forEach( el => el.querySelector('input[type="checkbox"]').onchange = SisEpi.Notifications.setNotificationReadState );
    };

    SisEpi.Notifications.setUpdateInterval = function()
    {
        if (SisEpi.Notifications.updateInterval)
            window.clearInterval(SisEpi.Notifications.updateInterval);

        SisEpi.Notifications.updateInterval = window.setInterval(SisEpi.Notifications.fetchLastNotifications, 60000);
    }

    window.addEventListener('load', function()
    {
        SisEpi.Notifications.setReadStateButtonHandler();   
        SisEpi.Notifications.setUpdateInterval();
    });
</script>

<span id="notificationPanel" tabindex="1">
    <span id="notificationPanelButton" tabindex="2" data-notifications="<?= $unread ?>">
        &#128276;
    </span>
    <div id="notificationDropdown" tabindex="3">
        <?php foreach ($lastNots as $not): ?>
        <div class="notificationItemBox" data-id="<?= $not->id ?>" data-is-read="<?= $not->isRead ? "1" : "0" ?>">
            <a href="<?= URL\URLGenerator::generateSystemURL('notifications', 'notificationlink', $not->id) ?>">
                <div class="notificationIconBox">
                    <img src="<?= URL\URLGenerator::generateFileURL($not->iconFilePath) ?>" height="50" />
                </div>
                <div class="notificationTextBox">
                    <h1><?= hsc($not->title) ?></h1>
                    <p><?= hsc($not->description) ?></p>
                    <span class="notificationDateTime"><?= date_create($not->dateTime)->format('d/m/y H:i:s') ?></span>
                </div>
                <div class="notificationReadStatusButton">
                    <label style="display:block;">
                        <input type="checkbox" <?= $not->isRead ? "" : "checked" ?> autocomplete="off"/>
                        <span class="notificationSetReadStatusCircle"></span>
                    </label>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
        <div class="notificationSeeMoreLink">
            <a href="<?= URL\URLGenerator::generateSystemURL('notifications') ?>">Ver todas</a>
        </div>
    </div>
</span>