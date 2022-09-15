function copyDivToClipboard() {
    var aux = document.createElement("input");
    aux.setAttribute("value", document.getElementById("SAML_display").textContent);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
    document.getElementById('copy').textContent = "Copied";
    document.getElementById('copy').style.background = "grey";
    window.getSelection().selectAllChildren( document.getElementById( "SAML_display" ) );

}

function test_download() {
    var filename = document.getElementById("SAML_type").textContent+".xml";
    var node = document.getElementById("SAML_display");
    htmlContent = node.innerHTML;
    text = node.textContent;
    console.log(text);
    var element = document.createElement('a');
    element.setAttribute('href', 'data:Application/octet-stream;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}