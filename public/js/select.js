var len = document.getElementById("ad_kindof").options.length;
var SelectElements = new Array;
var i =0;
var n = document.getElementById("ad_kindof").options.selectedIndex;
for (var n = 0; n < len; n++)
{
    if (document.getElementById("ad_kindof").options[n].selected==true)
    {
        SelectElements[i]=document.getElementById("ad_kindof").options[n].text;
        i++;
    }

}
//alert(SelectElements);