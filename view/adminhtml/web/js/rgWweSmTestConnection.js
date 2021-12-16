    require(["jquery", "domReady!"], function ($) {
        /* Test Connection Validation */
        rgWweSmAddTestConnTitle($);
        $('#rg-wwesm-test-conn').click(function () {
            if ($('#config-edit-form').valid()) {
                let ajaxURL = $(this).attr('connAjaxUrl');
                rgWweSmTestConnAjaxCall($, ajaxURL);
            }
        return false;
        });
    });
    
    /**
     * Assign Title to inputs
     */
    function rgWweSmAddTestConnTitle($)
    {
        let sectionId = '#WweSmConnSetting_ENWweSpqSecondAcc_';
        let data = {'rgAccountNumber' : 'Account Number',
                    'rgUsername' : 'Username',
                    'rgPassword' : 'Password',
                    'rgAuthenticationKey' : 'Authentication Key'
                    };

        for (let id in data) {
            let title = data[id];
            $(sectionId+id).attr('title', title);
        }
    }
    
    /**
     * Test connection ajax call
     * @param {object} $
     * @param {string} ajaxURL
     * @returns {function}
     */
    function rgWweSmTestConnAjaxCall($, ajaxURL){
        let sectionId = '#WweSmConnSetting_ENWweSpqSecondAcc_';
        let credentials = {
            accountNumber       : $(sectionId+'rgAccountNumber').val(),
            username            : $(sectionId+'rgUsername').val(),
            password            : $(sectionId+'rgPassword').val(),
            authenticationKey   : $(sectionId+'rgAuthenticationKey').val(),
            pluginLicenceKey    : $('#WweSmConnSetting_first_licenseKey').val()
        };

        if(!(credentials.accountNumber && credentials.username && credentials.password && credentials.authenticationKey)){
            let data = {Error : 'All fields are required'};
            rgWweSmTestConnResponse(data);
            return false;
        }

        wweSmAjaxRequest(credentials, ajaxURL, rgWweSmTestConnResponse);
    }
    
    /**
     * @param {object} data
     * @returns {void}
     */
    function rgWweSmTestConnResponse(data){
        let elemId = '#rg-wwesm-conn-response';
        let msgClass, msgText =  '';
        if (data.Error) {
            msgClass = 'error';
            msgText = data.Error;
        } else {
            msgClass = 'success';
            msgText = data.Success;
        }
        wweSmResponseMessage(elemId, msgClass, msgText);
    }