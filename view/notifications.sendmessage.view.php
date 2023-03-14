<?php if (isset($availableUserList)): ?>
    <script>
        function btnAddDestinationUser_onClick(e)
        {
            let span = document.getElementById('destinationUsersSpan');
            let cloned = span.querySelector('select').cloneNode(true);

            span.appendChild(cloned);

            if (span.children.length > 1) document.getElementById('btnRemoveDestinationUser').disabled = false;
        }

        function btnRemoveDestinationUser_onClick(e)
        {
            let span = document.getElementById('destinationUsersSpan');
            span.removeChild(span.lastChild);

            if (span.children.length === 1) this.disabled = true;
        }

        window.addEventListener('load', function()
        {
            document.getElementById('btnAddDestinationUser').onclick = btnAddDestinationUser_onClick;
            document.getElementById('btnRemoveDestinationUser').onclick = btnRemoveDestinationUser_onClick;
        });
    </script>
    <form method="post" action="<?= URL\URLGenerator::generateFileURL('post/notifications.sendmessage.post.php', [ 'cont' => $_GET['cont'], 'action' => 'home' ] ) ?>">
        <span class="formField">
            <label>Destinatário(s): </label>
            <span id="destinationUsersSpan">
                <select name="selDestinationUserId[]" required>
                    <?php foreach ($availableUserList as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                    <?php endforeach; ?>
                </select></span>
            <button type="button" id="btnAddDestinationUser" style="min-width: 40px;">+</button>
            <button type="button" id="btnRemoveDestinationUser" style="min-width: 40px;" disabled>-</button>
        </span>
        <span class="formField">
            <label>Mensagem:
                <textarea name="txtMessage" rows="5" maxlength="280" required></textarea>
            </label>
            <div style="font-size: small;">Até 280 caracteres</div>
        </span>
        <span class="formField">
            <label>Link/URL anexo: <input type="url" name="txtURL" size="40" /></label>
        </span>
        <input type="submit" name="btnsubmitSendMessage" value="Enviar" />
    </form>
<?php endif; ?>