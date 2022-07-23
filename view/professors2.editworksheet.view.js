
function selPaymentLevel_onChange(e)
{
    var [table, level] = this.value.split(':');

    document.getElementById('hidPaymentTable').value = table;
    document.getElementById('hidPaymentLevel').value = level;
}

function selSubsAllowancePaymentLevel_onChange(e)
{
    var [table, level] = this.value.split(':');

    document.getElementById('hidSubsAllowanceTable').value = table;
    document.getElementById('hidSubsAllowanceLevel').value = level;
}

function chkUseSubsAllowance_onChange(e)
{
    document.getElementById('fsSubsAllowance').style.display = this.checked ? 'block' : 'none';

    if (this.checked)
    {
        document.getElementById('hidSubsAllowanceTable').value = 0;
        document.getElementById('hidSubsAllowanceLevel').value = 0;
        document.getElementById('selSubsAllowancePaymentLevel').selectedIndex = 0;
        document.getElementById('numSubsAllowanceClassTime').value = 1;
    }
    else
    {
        document.getElementById('hidSubsAllowanceTable').value = null;
        document.getElementById('hidSubsAllowanceLevel').value = null;
        document.getElementById('numSubsAllowanceClassTime').value = null;
    }
}

window.addEventListener('load', function(e)
{
    this.document.getElementById('selPaymentLevel').onchange = selPaymentLevel_onChange;
    this.document.getElementById('chkUseSubsAllowance').onchange = chkUseSubsAllowance_onChange;
    this.document.getElementById('selSubsAllowancePaymentLevel').onchange = selSubsAllowancePaymentLevel_onChange;
});