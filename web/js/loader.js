$(function() {
    $('#observation_save').click(function () {
        var btn = $(this);
        $(btn).buttonLoader('start');
        $(btn).after('<div>Lors de l\'enregistrement, merci de ne pas recharger la page. </div>');
    });
});