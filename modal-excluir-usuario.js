$(document).ready(function () {
    $('a[excluir-usuario]').click(function (ev) {
        var href = $(this).attr('href');
        if(!$('#confirm-delete').length){
            $('body').append('<!-- Modal -->\n' +
                '<div class="modal fade" id="confirma-excluir-usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
                '  <div class="modal-dialog modal-dialog-centered" role="document">\n' +
                '    <div class="modal-content">\n' +
                '      <div class="modal-header" style="background-color: #efefef">\n' +
                '        <h5 class="modal-title badge badge-primary" id="exampleModalLabel" style="font-size: 16px">Excluir Usuário</h5>\n' +
                '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                '          <span aria-hidden="true">&times;</span>\n' +
                '        </button>\n' +
                '      </div>\n' +
                '      <div class="modal-body" style="background-color: #efefef">\n' +
                '        <span style="font-size: 20px">Deseja realmente excluir este Usuário?</span>\n' +
                '      </div>\n' +
                '      <div class="modal-footer" style="background-color: #efefef">\n' +
                '        <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>\n' +
                '        <a class="btn btn-danger text-white" id="dataConfirmOk">Sim</a>\n' +
                '      </div>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>');
        }
        $('#dataConfirmOk').attr('href', href);
        $('#confirma-excluir-usuario').modal({shown: true});
        return false;
    });
});
