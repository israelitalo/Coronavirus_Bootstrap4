//Coletar CEP digitado pelo usuário para usar API viaCep
$(function () {

    //Para limpar o formulário do cadastro de Hospitais.
    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $('#cepHospital').val("");
        $("#ruaHospital").val("");
        $("#bairroHospital").val("");
        $("#cidadeHospital").val("");
        $("#estadoHospital").val("");
    }

    //Coletando o CEP e preenchendo o restante do endereço do Hospital.
    $('#cepHospital').change(function () {
        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');
        if(cep != '' && cep != ' ' && cep.length == 8){
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)){
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#ruaHospital").val(dados.logradouro);
                        $("#bairroHospital").val(dados.bairro);
                        $("#cidadeHospital").val(dados.localidade);
                        $("#estadoHospital").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            }
        }else{
            alert("CEP "+cep+" é inválido! \nO campo CEP não pode ser vazio e deve ser digitado sem hífen.");
            $('#cepHospital').val('');
        }
    });
});

//Coletar CEP digitado pelo usuário para usar API viaCep no cadastro de Pacientes
$(function () {

    //Para limpar o formulário do cadastro de Hospitais.
    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $('#cepPaciente').val("");
        $("#ruaPaciente").val("");
        $("#bairroPaciente").val("");
        $("#cidadePaciente").val("");
        $("#estadoPaciente").val("");
    }

    //Coletando o CEP e preenchendo o restante do endereço do Hospital.
    $('#cepPaciente').change(function () {
        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');
        if(cep != '' && cep != ' ' && cep.length == 8){
            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)){
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#ruaPaciente").val(dados.logradouro);
                        $("#bairroPaciente").val(dados.bairro);
                        $("#cidadePaciente").val(dados.localidade);
                        $("#estadoPaciente").val(dados.uf);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            }
        }else{
            alert("CEP "+cep+" é inválido! \nO campo CEP não pode ser vazio e deve ser digitado sem hífen.");
            $('#cepPaciente').val('');
        }
    });
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
