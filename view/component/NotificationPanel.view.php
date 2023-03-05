<script>    
    function setNotificationReadState()
    {
        let itemBox = this.parentNode.parentNode.parentNode.parentNode;
        itemBox.setAttribute('data-is-read', this.checked ? '0' : '1');
    }

    function updateUnreadNotificationCountLabel(count)
    {
        document.getElementById('notificationPanelButton').setAttribute('data-notifications', count);
        document.querySelectorAll('.labelUnreadNotificationsCount').forEach( el => el.innerText = (count <= 0 ? 'Todas lidas' : count == 1 ? '1 não lida ' : count + ' não lidas') ); 
    }

    window.addEventListener('load', function()
    {
        let elements = document.querySelectorAll('.notificationItemBox');
        elements.forEach( el => el.querySelector('input[type="checkbox"]').onchange = setNotificationReadState );
    });
</script>

<span id="notificationPanel" tabindex="1">
    <span id="notificationPanelButton" tabindex="2" data-notifications="50">
        &#128276;
    </span>
    <div class="notificationDropdown" tabindex="3">
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <div class="notificationItemBox" data-id="<?= $i ?>" data-is-read="<?= $i <= 3 ? "1" : "0" ?>">
            <a href="#">
                <div class="notificationIconBox">
                    <img src="/sisepi/pics/notifications/bell-by-pixel-perfect.png" height="50" />
                </div>
                <div class="notificationTextBox">
                    <h1>Notificação teste</h1>
                    <p>Teste teste</p>
                    <span class="notificationDateTime">27/02/2023 23:00:00</span>
                </div>
                <div class="notificationReadStatusButton">
                    <label style="display:block;">
                        <input type="checkbox" <?= $i <= 3 ? "" : "checked" ?> />
                        <span class="notificationSetReadStatusCircle"></span>
                    </label>
                </div>
            </a>
        </div>
        <?php endfor; ?>
        <div class="notificationSeeMoreLink">
            <a href="<?= URL\URLGenerator::generateSystemURL('notifications') ?>">Ver todas</a>
        </div>
    </div>
</span>