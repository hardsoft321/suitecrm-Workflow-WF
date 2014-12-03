if(!lab321) var lab321 = {};
if(!lab321.wf) lab321.wf = {};

if(!lab321.getSugarListViewCheckedRecords) {
    lab321.getSugarListViewCheckedRecords = function() {
        var mode = document.MassUpdate.select_entire_list && document.MassUpdate.select_entire_list.value == 1 ? 'entire' : 'selected';
        var ar = [];
        if(mode != 'entire') {
            $('#MassUpdate input[name="mass[]"]:checked').each(function(){
                ar.push($(this).val());
            });
        }
        return {
            mode: mode,
            items: ar
        };
    };
}

lab321.wf.setStatusOptions = function(statuses) {
    var html = '';
    var selectedStatus = $('#newStatus').val();
    for(var key in statuses) {
        if(typeof statuses[key] == 'string') {
            html += '<option value="'+key+'"'+(selectedStatus == key ? ' selected="selected"' : '')+'>'+statuses[key]+'</option>';
        }
    }
    $('#newStatus').html(html);
};

lab321.wf.confirmStatus = function() {
    if(!lab321.wf.confirmRequest) {
        lab321.wf.confirmRequest = {};
    }
    if(lab321.wf.confirmRequest.status == 'sent') {
        return;
    }
    ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_SAVING'));
    lab321.wf.confirmRequest.status = 'sent';
    lab321.wf.confirmRequest.saveButtonOnclickValue = $('#confirm input[type="submit"]').attr('onclick');
    $('#confirm input[type="submit"]').attr('onclick', 'return false');

    $.ajax('index.php?entryPoint=wf_confirm', {
        data: {
            record: $('#confirm #record').val(),
            module: $('#confirm #module').val(),
            status: $('#confirm select[name="status"]').val(),
            assigned_user: $('#confirm select[name="assigned_user"]').val(),
            resolution: $('#confirm #resolution').val(),
            is_ajax_call: 1,
        },
        type: 'POST',
        dataType: 'json'
    }).done(function(data) {
        lab321.wf.setConfirmErrors(data.errors);
        ajaxStatus.hideStatus();
        if(data.saved) {
            ajaxStatus.flashStatus(SUGAR.language.get('app_strings','LBL_SAVED'), 3000);
            location.reload();
        }
    }).fail(function() {
        ajaxStatus.hideStatus();
    }).always(function() {
        $('#confirm input[type="submit"]').attr('onclick', lab321.wf.confirmRequest.saveButtonOnclickValue || '');
        lab321.wf.confirmRequest.status = 'done';
    });
}

lab321.wf.massConfirmSave = function() {
    lab321.wf.massConfirm('save');
};

lab321.wf.massConfirm = function(action) {
    if(!lab321.wf.massConfirmRequest) {
        lab321.wf.massConfirmRequest = {};
    }
    if(lab321.wf.massConfirmRequest.status == 'resend') {
        return;
    }
    if(lab321.wf.massConfirmRequest.status == 'sent') {
        lab321.wf.massConfirmRequest.status = 'resend';
        lab321.wf.massConfirmRequest.resendAction = action;
        return;
    }
    if(action == 'save') {
        ajaxStatus.showStatus(SUGAR.language.get('app_strings','LBL_SAVING'));
    }
    lab321.wf.massConfirmRequest.status = 'sent';
    lab321.wf.massConfirmRequest.bSubmitAfterCheck = false;
    lab321.wf.massConfirmRequest.saveButtonOnclickValue = $('#confirm input[type="submit"]').attr('onclick');
    
    var checkedRecords = lab321.getSugarListViewCheckedRecords();
    $('#confirm input[type="submit"]').attr('onclick', 'lab321.wf.massConfirmRequest.bSubmitAfterCheck = true; return false');

    var module = $('#MassUpdate input[name="module"]').val();
    
    $.ajax('index.php?entryPoint=wf_mass_confirm', {
        data: {
            action: action,
            checkedRecords: checkedRecords,
            module: module,
            status: $('#confirm select[name="status"]').val(),
            assigned_user: $('#confirm select[name="assigned_user"]').val(),
            resolution: action == 'save' ? $('#resolution').val() : '',
        },
        type: 'POST',
        dataType: 'json'
    }).done(function(data) {
        lab321.wf.setConfirmErrors(data.errors);
        if(data.editFormData) {
            lab321.wf.assignedUsers = data.editFormData.assignedUsers || [];
            lab321.wf.setStatusOptions((data.editFormData.confirmData || {}).newStatuses || []);
        }
        lab321.wf.onChangeNewStatus();
        ajaxStatus.hideStatus();
        if(data.saved) {
            ajaxStatus.flashStatus(SUGAR.language.get('app_strings','LBL_SAVED'), 3000);
            $('#resolution').val('');
        }
    }).fail(function() {
        ajaxStatus.hideStatus();
    }).always(function() {
        $('#confirm input[type="submit"]').attr('onclick', lab321.wf.massConfirmRequest.saveButtonOnclickValue || '');
        if(lab321.wf.massConfirmRequest.bSubmitAfterCheck) {
            $('#confirm input[type="submit"]').click();
        }
        lab321.wf.massConfirmRequest.bSubmitAfterCheck = false;
        var status = lab321.wf.massConfirmRequest.status;
        lab321.wf.massConfirmRequest.status = 'done';
        if(status == 'resend') {
            lab321.wf.massConfirm(lab321.wf.massConfirmRequest.resendAction);
        }
    });
};

lab321.wf.setListViewHandlers = function() {
    var origCheckItem = sugarListView.prototype.check_item;
    sugarListView.prototype.check_item = function(cb, form) {
        origCheckItem(cb, form);
        updateAvailableStatuses();
    };
    var origClearAll = sugarListView.prototype.clear_all;
    sugarListView.prototype.clear_all = function() {
        origClearAll();
        updateAvailableStatuses();
    };
    var origCheckAll = sugarListView.prototype.check_all;
    sugarListView.prototype.check_all = function(form, field, value, pageTotal) {
        origCheckAll(form, field, value, pageTotal);
        updateAvailableStatuses();
    };
    var origCheckEntire = sugarListView.prototype.check_entire_list;
    sugarListView.prototype.check_entire_list = function(form, field, value, list_count) {
        origCheckEntire(form, field, value, list_count);
        updateAvailableStatuses();
    };
    
    function updateAvailableStatuses() {
        if($('#confirm_block').is(':visible') && $('#confirm_panel').is(':visible')) {
            lab321.wf.massConfirm('check');
        }
        else {
            if(!lab321.wf.massConfirmRequest) {
                lab321.wf.massConfirmRequest = {};
            }
            if(lab321.wf.massConfirmRequest.status != 'sent') {
                lab321.wf.massConfirmRequest.status = 'delay';
            }
        }
    }
};

lab321.wf.setConfirmErrors = function(errors) {
    var html = '';
    html = '<ul>';
    if(errors.length > 0) {
        html += '<li>Чтобы перевести запись на выбранный статус, необходимо исправить следующие ошибки:</li>';
    }
    for(var i in errors) {
        if(typeof errors[i] == 'string') { //jit.js добавляет Array.prototype.sum
            html += '<li>'+errors[i]+'</li>';
        }
    }
    html += '</ul>';
    $('#confirm .errors').html(html);
};

lab321.wf.togglePanel = function() {
    var id = 'confirm_panel';
    var panel = document.getElementById(id);
    if (panel.style.display == 'none') {
        panel.style.display = 'block';
        document.getElementById(id + "_toggle_img").src = 'themes/default/images/basic_search.gif';
        if ((lab321.wf.massConfirmRequest || {}).status == 'delay') {
            lab321.wf.massConfirm('check');
        }
    } else {
        panel.style.display = 'none';
        document.getElementById(id + "_toggle_img").src = 'themes/default/images/advanced_search.gif';
    }
}

lab321.wf.onChangeNewStatus = function() {
    var statusSel = document.getElementById('newStatus');
    if (!statusSel)
        return;
    var disable = true;
    var userSel = document.confirmForm.assigned_user;
    userSel.options.length = 0;
    if (statusSel.length > 0) {
        var status = statusSel[statusSel.selectedIndex].value;
        if (status != "" && lab321.wf.assignedUsers[status] !== undefined && lab321.wf.assignedUsers[status].length > 0) {
            disable = false;

            for (i = 0; i < lab321.wf.assignedUsers[status].length; i++)
                userSel.options[i] = new Option(lab321.wf.assignedUsers[status][i][1], lab321.wf.assignedUsers[status][i][0]);
        }
    }
    document.confirmForm.submit_btn.disabled = disable;
}

lab321.wf.onChangeRole = function() {
    var masterSel = document.getElementById('role');
    if (!masterSel)
        return;
    if (masterSel.length > 0) {
        var status = masterSel[masterSel.selectedIndex].value;
        var userSel = document.assign.new_assign_user;
        userSel.options.length = 0;
        if (status != "" && lab321.wf.confirmUsers[status] !== undefined && lab321.wf.confirmUsers[status].length > 0) {
            for (i = 0; i < lab321.wf.confirmUsers[status].length; i++)
                userSel.options[i] = new Option(lab321.wf.confirmUsers[status][i][1], lab321.wf.confirmUsers[status][i][0]);
        }
    }
}
