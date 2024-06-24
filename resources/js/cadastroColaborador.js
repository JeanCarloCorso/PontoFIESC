document.addEventListener('DOMContentLoaded', function () {
    const cpfInput = document.getElementById('cpf');

    cpfInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        
        value = value.padEnd(11, '_');
        value = value.slice(0, 3) + '.' + value.slice(3, 6) + '.' + value.slice(6, 9) + '-' + value.slice(9, 11);

        e.target.value = value;
    });

    cpfInput.addEventListener('keydown', function (e) {
        const allowedKeys = [8, 37, 38, 39, 40];
        if (!allowedKeys.includes(e.keyCode) && (e.keyCode < 48 || e.keyCode > 57)) {
            e.preventDefault();
        }
    });
});