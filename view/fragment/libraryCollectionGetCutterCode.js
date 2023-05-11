
SisEpi.Library = SisEpi.Library || {};
SisEpi.Library.Collection = SisEpi.Library.Collection || {};

SisEpi.Library.Collection.getCutterCode = async function(authorName, titleName)
{
    if (typeof(authorName) === 'string')
    {
        try
        {
            const url = SisEpi.Url.fileBaseUrl + `/generate/getCutterSanbornCode.php?name=${authorName}&title=${titleName}`; 
            const res = await fetch(url);
            const json = await res.json();

            if (json.error) throw new Error(json.error);

            if (SisEpi.Library.Collection.setCutterCodeDataFunction)
                SisEpi.Library.Collection.setCutterCodeDataFunction(json.data);
            else
                throw new Error("Não foi possível carregar código da tabela Cutter-Sanborn.");
        }
        catch (err)
        {
            showBottomScreenMessageBox(BottomScreenMessageBoxType.error, err.message);
        }
    }
};

SisEpi.Library.Collection.btnLoadCutterCode_onClick = function(e)
{
    const authorName = SisEpi.Library.Collection.getAuthorNameFunction();
    const titleName = SisEpi.Library.Collection.getTitleFunction();
    SisEpi.Library.Collection.getCutterCode(authorName, titleName);
};

SisEpi.Library.Collection.setCutterCodeDataFunction = null;
SisEpi.Library.Collection.getAuthorNameFunction = null;
SisEpi.Library.Collection.getTitleFunction = null;
SisEpi.Library.Collection.buttonLoadCutterCode = null;

function setUpCutterCodeLoader(props)
{
    SisEpi.Library.Collection.setCutterCodeDataFunction = props.setData;
    SisEpi.Library.Collection.getAuthorNameFunction = props.getName;
    SisEpi.Library.Collection.getTitleFunction = props.getTitle;
    SisEpi.Library.Collection.buttonLoadCutterCode = props.buttonLoad;
}

window.addEventListener('load', function()
{
    if (SisEpi.Library.Collection.buttonLoadCutterCode)
        SisEpi.Library.Collection.buttonLoadCutterCode.onclick = SisEpi.Library.Collection.btnLoadCutterCode_onClick;
});