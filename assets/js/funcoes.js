function formatNumber(num) {
    a = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
    return a.toFloat();

}

function number_format1(number, decimals, dec_point, thousands_sep) {
// *     example: number_format(1234.56, 2, ',', ' ');
// *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function f(value){
        //Padroniza valores com duas casas decimais após a vírgula
        var str = sprintf(“%.2f”, value);

       //Substitui todos as vírgulas por pontos
       var pos = str.indexOf(“,”);
       while (pos > -1){
              str = str.replace(“,”, “.”);
              pos = str.indexOf(“,”);
       }

       //Substitui o último ponto por vírgula
       str = str.substring(0,str.length-3) + str.substring(str.length-3, str.length).replace(“.”,”,”);

       //Retorna o valor formatado para visualização (Se quiser, aqui pode ser adicionado a       unidade: R$, $, etc…)
       return (str);
}


    //código usando jQuery

