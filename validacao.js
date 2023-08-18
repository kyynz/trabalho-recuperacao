function validarLivro() {
    var nome = document.getElementById('nome').value;
    var sobrenome = document.getElementById('sobrenome').value;
    var idade = document.getElementById('idade').value;
    var genero = document.getElementById('genero').value;


    var divErro = document.getElementById('divErro');



    //Validar se preenchido
    if(nome == '') {
        divErro.innerHTML = "Informe o Nome!";
        return false;
    
    } else if(sobrenome == '') {
        divErro.innerHTML = "Informe o Sobrenome!";
        return false;
    
    } else if(idade == '') {
        //alert("Informe a quantidade de páginas!");
        divErro.innerHTML = "Informe Idade!";
        return false;
    }else if(genero == '') 
        divErro.innerHTML = "Informe o Genero!";
        return false;

    return true;
}