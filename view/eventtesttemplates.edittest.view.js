
SisEpi.Events = SisEpi.Events || {};
SisEpi.Events.Tests = SisEpi.Events.Tests || {};
SisEpi.Events.Tests.Templates = SisEpi.Events.Tests.Templates || {};

SisEpi.Events.Tests.Templates.btnNewQuestion_onClick = function(self)
{
    const blueprint = document.querySelector('#itemQuestion .eventQuestionItem').cloneNode(true);
    blueprint.setAttribute("data-page-question-id", ++lastQuestIndex); //lastQuestIndex -> const in edittest.view.php
    blueprint.querySelector('.radCorrectAnswerQuest').name += blueprint.getAttribute("data-page-question-id"); 
    blueprint.querySelector('.radCorrectAnswerQuest').required = true;
    blueprint.querySelector('.txtQuestionText').required = true;
    blueprint.querySelector('.spanOptionInput input').required = true;

    const questionsBlock = document.querySelector('.eventTestQuestionsBlock');

    questionsBlock.appendChild(blueprint);
};

SisEpi.Events.Tests.Templates.btnDeleteQuestion_onClick = function(self)
{
    const item = self.parentNode;
    const block = item.parentNode;

    block.removeChild(item);
};

SisEpi.Events.Tests.Templates.btnMoveQuestionUp_onClick = function(self)
{
    const item = self.parentNode;
    const block = item.parentNode;

    if (item.previousElementSibling)
        block.insertBefore(item, item.previousElementSibling);
};

SisEpi.Events.Tests.Templates.btnMoveQuestionDown_onClick = function(self)
{
    const item = self.parentNode;
    const block = item.parentNode;

    if (item.nextElementSibling)
        block.insertBefore(item.nextElementSibling, item);
};

SisEpi.Events.Tests.Templates.btnAddOption_onClick = function(self)
{
    const blueprint = document.querySelector('#itemQuestion ol li').cloneNode(true);
    blueprint.querySelector('input').required = true;
    const item = self.parentNode;
    const ol = item.querySelector('ol');

    const radio = blueprint.querySelector('.radCorrectAnswerQuest');
    radio.name += item.getAttribute('data-page-question-id');

    ol.appendChild(blueprint);
};

SisEpi.Events.Tests.Templates.btnDeleteOption_onClick = function(self)
{
    const item = self.parentNode;
    const ol = item.parentNode;

    ol.removeChild(item);
};

SisEpi.Events.Tests.Templates.selOptionType_onChange = function(self)
{
    let blueprint;
    const li = self.parentNode;
    const oldOption = li.querySelector('.spanOptionInput');
    switch (self.value)
    {
        case "image": 
            blueprint = document.querySelector('#questionOptionImage .spanOptionInput').cloneNode(true); break;
        case "string": default:
            blueprint = document.querySelector('#questionOptionString .spanOptionInput').cloneNode(true); break;
    }
    blueprint.querySelector('input').required = true;
    li.replaceChild(blueprint, oldOption);
};

SisEpi.Events.Tests.Templates.fileOptionImage_onChange = function(self)
{
    function getBase64(file) 
    {
        return new Promise( (res, rej) =>
        {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => res(reader.result);
            reader.onerror = rej;
        });
     }

    const span = self.parentNode;
    const img = span.querySelector('.imgOptionImage');

    const [file] = self.files;
    if (file)
    {
        if (file.size > (100 * 1024)) // 100 KB
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, "Erro: A imagem não deve ter mais do que 100 KB.");
            img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            const dt = new DataTransfer();
            self.files = dt.files;
            return;
        }
        getBase64(file).then( res => img.src = res).catch(console.log);
    }
};

SisEpi.Events.Tests.Templates.validateForm = function()
{
    const questions = document.querySelectorAll('.eventTestQuestionsBlock .eventQuestionItem');
    if (questions.length < 1)
    {
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, "Erro: É necessário haver pelo menos uma questão.");
        return false;
    }

    const questWithoutOptions = Array.from(questions).find( quest => quest.querySelectorAll('ol li').length < 1 );
    if (questWithoutOptions)
    {
        showBottomScreenMessageBox(BottomScreenMessageBoxType.error, "Erro: Cada questão deve ter ao menos uma alternativa.");
        return false;
    }

    return true;
};

SisEpi.Events.Tests.Templates.generateTemplateJson = function()
{
    const output = 
    {
        title: document.querySelector('.txtTestTitle').value,
        percentForApproval: Number(document.querySelector('.numMinPercentForApproval').value),
        classTimeHours: Number(document.querySelector('.numClassTime').value),
        randomizeQuestions: document.querySelector('.chkRandomizeQuestions').checked,
        questions: Array.from(document.querySelectorAll('.eventTestQuestionsBlock .eventQuestionItem')).map( questionDiv =>
        {
            const radios = questionDiv.querySelectorAll('ol li .radCorrectAnswerQuest');
            const options = questionDiv.querySelectorAll('ol li');
            return {
                questText: questionDiv.querySelector('.txtQuestionText').value,
                correctAnswer: Array.from(radios).reduce( (prev, curr, index) => curr.checked ? index : prev, 0 ),
                randomize: questionDiv.querySelector('.chkRandomize').checked,
                options: Array.from(options).map( li =>
                {
                    const type = li.querySelector('.selOptionType').value;
                    switch (type)
                    {
                        case "image":
                            return { type, value: li.querySelector('.spanOptionInput .imgOptionImage').src };
                        case "string":
                        default:
                            return { type, value: li.querySelector('.spanOptionInput input').value };
                    }
                })
            };
        })
    };

    return JSON.stringify(output);
};