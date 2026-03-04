document.addEventListener('DOMContentLoaded', function () {
    if (window.AutoAlertConfig && window.AutoAlertConfig.confirm_delete) {
        document.addEventListener('submit', function (e) {
            const form = e.target;
            const methodField = form.querySelector('input[name="_method"]');
            
            if (form.method && form.method.toUpperCase() === 'POST' && methodField && methodField.value.toUpperCase() === 'DELETE') {
                if (window.AutoAlert && window.AutoAlert.confirmDelete) {
                    if (!form.dataset.autoAlertConfirmed) {
                        e.preventDefault();
                        
                        const proxyForm = {
                            submit: function() {
                                form.dataset.autoAlertConfirmed = 'true';
                                form.submit();
                            }
                        };
                        
                        window.AutoAlert.confirmDelete(proxyForm);
                    }
                }
            }
        });
    }
});
