@include('includes.stock.Stock_Ajax.public_function')
<script>
    $(document).ready(function() {
        bindBranchChangeEvent(); // Bind change event when the document is ready
        bindGroupsChangeEvent();
    });

    // handle csrf request header

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    })

    // Common function to handle AJAX errors
    function handleAjaxError(reject) {
        let response = $.parseJSON(reject.responseText);
        $.each(response.errors, function(key, val) {
            errorMsg(val[0]);
        });
    }

    // Common function to error response message
    function handleResponseMessageError(message, title, icon) {
        Swal.fire({
            title: title,
            text: message,
            icon: icon,
            timer: 5000
        });
    }

    let preventChangeEvent = false; // Flag to control change event execution
    let preventGroupsChangeEvent = false; // Flag to control change event execution

    const tbody = $('table.table-data tbody');
    let spinner = $(
        '<div class="spinner-border text-light" style="width: 18px; height: 18px;" role="status"><span class="sr-only">Loading...</span></div>'
    );



    // get sub groups 
    $(document).on('change', '#storeMaterial select[name="main_group_id"]', function() {
        let slectedMainGroupEle = $(this)
        let slectedSubMainGroupEle = $('#storeMaterial select[name="sub_group_id"]')
        let selectedValue = slectedMainGroupEle.val();
        let selectedText = slectedMainGroupEle.find('option:selected').text();
        if (!selectedValue) return;

        const url = '{{ url('stock/material/groups') }}/' + selectedValue + '/filter';
        const initialParams = {
            "parent_id": selectedValue,
        };
        const queryString = $.param(initialParams);


        $.ajax({
            type: "GET",
            url: `${url}?${queryString}`,
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    slectedSubMainGroupEle.html('');
                    const subGroups = response.data;
                    slectedSubMainGroupEle.prop('disabled', false)

                    if (!subGroups.length) {
                        slectedSubMainGroupEle.append(
                            '<option value="">لاتوجد مجموعات فرعية</option>');
                        return;
                    }
                    subGroups.forEach(group => {
                        const newGroupsOptions =
                            `<option value="${group.id}">${group.name}</option>`
                        slectedSubMainGroupEle.append(newGroupsOptions)
                    });
                }

            },
            error: handleAjaxError,
        })
    });

    // get sections
    $(document).on('change', '#storeMaterial select[name="branch_id"]', function() {
        let slectedMainGroupEle = $(this)
        let sectionCheckboxEle = $('.section_id')
        let selectedValue = slectedMainGroupEle.val();
        let selectedText = slectedMainGroupEle.find('option:selected').text();
        if (!selectedValue) return;


        $.ajax({
            type: "GET",
            url: '{{ url('stock/material/sections') }}/' + selectedValue + '/filter',
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    const sections = response.data;
                    let sectionsContent = '';
                    sectionCheckboxEle.html('');
                    if (!sections.length) {
                        sectionsContent =
                            `<div class='col-md-12 d-flex align-items-center mt-2 '>
                                    <p>لاتوجد أقسام متاحة</p>
                            </div>`;
                    }
                    sections.forEach(function(section) {
                        sectionsContent += `
                        <div class='col-md-4 d-flex align-items-center mt-2 '>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                value="${section.name}" 
                                id="section-${section.id}"
                                name="sections">
                                <label class="form-check-label"
                                for="section-${section.id}">
                                    ${section.name}
                                </label>
                            </div>
                        </div>`;
                    })
                    sectionCheckboxEle.html(sectionsContent);

                }

            },
            error: handleAjaxError,
        })
    });


    // store section

    $(document).on('submit', '#storeMaterial', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        const originalHtml = button.html();
        button.html(spinner).prop('disabled', true);
        const storeModel = $('#storeMaterial');
        const formData = collectMaterialData(form);
        $.ajax({
            type: 'POST',
            url: "{{ route('stock.materials.store') }}",
            dataType: 'json',
            data: {
                name: formData.name,
                cost: formData.cost,
                price: formData.price,
                unit: formData.unit,
                // 
                group_id: formData.subGroupId,
                branch_id: formData.branchId,
                // 
                min_store: formData.storeLimitMin,
                max_store: formData.storeLimitMax,
                min_section: formData.sectionLimitMin,
                max_section: formData.sectionLimitMax,
                // 
                loss_ratio: formData.lossRatio,
                expire_date: formData.expireDate,
                storage_type: formData.storageType,
                // 
                material_type: formData.selectedMaterialType,
                sectionIds: formData.sections,
            },
            success: function(response) {
                console.log(response);
                if (response.status == 200) {
                    newMaterial = `<tr id=sid${response.data.id}>
                            <td>${response.data.id}</td>
                            <td>${response.data.group.name}</td>
                            <td>${response.data.name}</td>
                                                    <td>
                                    <button title="تعديل" class="btn btn-success"
                                        data-id="${response.data.id}" id="edit_material">

                                        <i class="far fa-edit"></i>
                                    </button>

                                    <button title="عرض" data-id="${response.data.id}" id="view_material"
                                        class="btn btn-primary">

                                        <i class="fa fa-eye" aria-hidden="true"></i>

                                    </button>
                                    <button class="btn btn-danger" id="delete_material"
                                        data-id="${response.data.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                            </td>
                            </tr>`;
                    $('tbody tr').each(function() {
                        if ($(this).find('td').attr('colspan') == '4') {
                            $(this).remove();
                        }
                    });
                    tbody.append(newMaterial);

                    resetModalForm(storeModel)
                    $('#id').val(response.data.id + 1);

                    successMsg(response.message);
                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // delete section
    $(document).on('click', '#delete_material', function() {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        Swal.fire({
            if (response.status == 422) {
                                handleResponseMessageError(response.message,
                                    'خطأ', 'error')
                                resolve();
                            }
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        type: 'DELETE',
                        url: '{{ url('stock/materials', '') }}' + '/' + id,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 200) {
                                handleResponseMessageError(response.message,
                                    'تم الحذف', 'success')

                                row.remove();
                                resolve();
                            }

                            if (response.status == 422) {
                                handleResponseMessageError(response.message,
                                    'خطأ', 'error')
                                resolve();
                            }
                        },
                        error: function(error) {
                            handleResponseMessageError(error.responseJSON
                                .message, 'خطأ', 'error')
                            resolve();
                        },
                        complete: function() {
                            if (tbody.find('tr').length === 0) {
                                tbody.append(
                                    '<tr><td colspan="4">لا توجد خامات</td></tr>'
                                );
                            }
                        }
                    });
                });
            }
        });
    });

    // view section
    $(document).on('click', '#view_material', function() {
        const id = $(this).data('id');
        const viewModal = $('#viewModal');
        resetModalForm(viewModal);
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);

        $.ajax({
            type: 'GET',
            url: '{{ url('stock/materials', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    updateModelForm(viewModal, response)
                    checkForm();
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }

        });

    })
    // edit section
    $(document).on('click', '#edit_material', function() {
        const id = $(this).data('id');
        const viewModal = $('#editModal');
        // Set the flag to prevent the change event
        resetModalForm(viewModal);
        preventChangeEvent = true;
        preventGroupsChangeEvent = true;
        let button = $(this);
        let originalHtml = button.html();
        button.html(spinner);
        button.prop('disabled', true);

        $.ajax({
            type: 'GET',
            url: '{{ url('stock/materials', '') }}' + '/' + id,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    updateModelForm(viewModal, response)
                    $('#editModal').find('.modal-footer #update_material').removeAttr('data-id')
                        .data('id', response.data.id)

                    checkForm();
                }
                // Unset the flag after setting new values
                preventChangeEvent = false;
                preventGroupsChangeEvent = false;
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
                // Unset the flag after setting new values
                preventChangeEvent = false;
                preventGroupsChangeEvent = false;
            }

        });
    });


    // update section 
    $(document).on('click', '#update_material', function() {
        const id = $(this).data('id');
        let button = $(this);
        let originalHtml = button.html();
        const form = $('#editModal');
        button.html(spinner).prop('disabled', true);
        const formData = collectMaterialData(form);
        $.ajax({
            type: 'PUT',
            url: '{{ url('stock/materials', '') }}' + '/' + id,
            dataType: 'json',
            data: {
                name: formData.name,
                cost: formData.cost,
                price: formData.price,
                unit: formData.unit,
                // 
                group_id: formData.subGroupId,
                branch_id: formData.branchId,
                // 
                min_store: formData.storeLimitMin,
                max_store: formData.storeLimitMax,
                min_section: formData.sectionLimitMin,
                max_section: formData.sectionLimitMax,
                // 
                loss_ratio: formData.lossRatio,
                expire_date: formData.expireDate,
                storage_type: formData.storageType,
                // 
                material_type: formData.selectedMaterialType,
                sectionIds: formData.sections,
            },
            success: function(response) {
                if (response.status == 200) {
                    $('#sid' + response.data.id + ' td:nth-child(1)').text(response.data
                        .id)
                    $('#sid' + response.data.id + ' td:nth-child(2)').text(response.data.group.name)
                    $('#sid' + response.data.id + ' td:nth-child(3)').text(response.data.name)
                    successMsg(response.message);
                    $('#editModal').modal('hide');
                }
            },
            error: handleAjaxError,
            complete: function() {
                button.html(originalHtml).prop('disabled', false);
            }
        });
    })

    // collect saving new material data
    function collectMaterialData(form) {
        //
        let mainGroupId = form.find("select[name='main_group_id']").val();
        let subGroupId = form.find("select[name='sub_group_id']").val();
        let branchId = form.find("select[name='branch_id']").val();
        // 
        let name = form.find("input[name='name']").val();
        let cost = form.find("input[name='cost']").val();
        let price = form.find("input[name='price']").val();
        let unit = form.find("select[name='unit']").val();
        // 
        let storeLimitMin = form.find("input[name='store_limit_min']").val();
        let storeLimitMax = form.find("input[name='store_limit_max']").val();
        let sectionLimitMin = form.find("input[name='section_limit_min']").val();
        let sectionLimitMax = form.find("input[name='section_limit_max']").val();
        // 
        let lossRatio = form.find("input[name='loss_ratio']").val();
        let expireDate = form.find("input[name='expire_date']").val();
        let storageType = form.find("select[name='storage_type']").val();
        // 
        let selectedMaterialType = form.find("input[name='materialType']:checked").val();
        const sectionsChecked = form.find('input[name="sections"]:checked');
        let sections;
        if (!sectionsChecked.length) {
            sections = [];
        } else {
            if (form.is('#editModal')) {
                sections = sectionsChecked.map((_, el) => ({
                    id: $(el).attr('id').replace('edit-model-section-', '')
                })).get();
            } else {
                sections = sectionsChecked.map((_, el) => ({
                    id: $(el).attr('id').replace('section-', '')
                })).get();
            }
        }



        return {
            name: name,
            cost: cost,
            price: price,
            unit: unit,
            // 
            mainGroupId: mainGroupId,
            subGroupId: subGroupId,
            branchId: branchId,
            // 
            storeLimitMin: storeLimitMin,
            storeLimitMax: storeLimitMax,
            sectionLimitMin: sectionLimitMin,
            sectionLimitMax: sectionLimitMax,
            // 
            lossRatio: lossRatio,
            expireDate: expireDate,
            storageType: storageType,
            // 
            selectedMaterialType: selectedMaterialType,
            sections: sections,
        };

    }

    // Common function to reset modal form
    function resetModalForm(formElement) {

        const name = $(formElement).find("input[name='name']")
        const cost = $(formElement).find("input[name='cost']")
        const price = $(formElement).find("input[name='price']")
        const min_store = $(formElement).find("input[name='store_limit_min']")
        const max_store = $(formElement).find("input[name='store_limit_max']")
        const min_section = $(formElement).find("input[name='section_limit_min']")
        const max_section = $(formElement).find("input[name='section_limit_max']")
        const loss_ratio = $(formElement).find("input[name='loss_ratio']")
        const expire_date = $(formElement).find("input[name='expire_date']")
        //   branch select
        const branchSelect = $(formElement).find('select[name="branch_id"]');
        const firstBranchOptionValue = branchSelect.val('');
        //   unit select
        const unitSelect = $(formElement).find('select[name="unit"]');
        const firstUnitOptionValue = unitSelect.find('option:first').val();
        //   main group select
        const mainGroupSelect = $(formElement).find('select[name="main_group_id"]');
        const firstMainGroupOptionValue = mainGroupSelect.find('option:first').val();
        //   sub group select
        const subGroupSelect = $(formElement).find('select[name="sub_group_id"]');
        //   storage type select
        const storageTypeSelect = $(formElement).find('select[name="storage_type"]');
        const firstStorageTypeOptionValue = storageTypeSelect.find('option:first').val();
        // material type checkbox
        $(formElement).find('input[type="radio"]').prop('checked', false),

            $(formElement).find('input[type="checkbox"]').prop('checked', false);

        $(formElement).find('.view_model_material_type').html('')

        const sections = $(formElement).find('.section_id')
        // set values
        name.val('')
        cost.val('')
        price.val('')
        min_store.val('')
        max_store.val('')
        min_section.val('')
        max_section.val('')
        loss_ratio.val('')
        expire_date.val('')
        branchSelect.val(firstBranchOptionValue).change();
        unitSelect.val(firstUnitOptionValue).change();
        mainGroupSelect.val(firstMainGroupOptionValue).change();
        subGroupSelect.empty().append('<option selected disabled>اختر المجموعة الفرعية</option>').change();
        storageTypeSelect.val(firstStorageTypeOptionValue).change();
        sections.html('')
    }

    function updateModelForm(model, response) {

        //   view model
        model.modal('show');
        // update values

        $(model).find("input[name='id']").val(response.data.id);


        $(model).find('select[name="sub_group_id"]').append(
            `<option  value="${response.data.group.id}" selected>${response.data.group.name}</option>`
        );

        // select option
        let selectBranchEle = $(model).find('select[name="branch_id"]')
        let selectMainGroupEle = $(model).find('select[name="main_group_id"]')
        let selectUnitEle = $(model).find('select[name="unit"]')
        let selectStorageEle = $(model).find('select[name="storage_type"]')


        let branchOption = selectBranchEle.find(
            `option:contains(${response.data.branch.name})`).val();
        let mainGroupOption = selectMainGroupEle.find(
            `option:contains(${response.data.group.parent_name})`).val();
        let unitOption = selectUnitEle.find(
            `option:contains(${response.data.unit.name_ar})`).val();
        let storageOption = selectStorageEle.find(
            `option:contains(${response.data.storage_type.name_ar})`).val();

        if (branchOption) {
            selectBranchEle.val(branchOption).change();

        } else {
            selectBranchEle.append(
                `<option value="${response.data.branch.id}" selected>${response.data.branch.name}</option>`
            )
        }

        if (mainGroupOption) {
            selectMainGroupEle.val(mainGroupOption).change();
        } else {
            selectMainGroupEle.append(
                `<option value="${response.data.group.parent_id}" selected>${response.data.group.parent_name}</option>`

            )
        }
        if (unitOption) {
            selectUnitEle.val(unitOption).change();
        } else {
            selectUnitEle.append(
                `<option value="${response.data.unit.name_en}" selected>${response.data.unit.name_ar}</option>`

            )
        }

        if (storageOption) {
            selectStorageEle.val(storageOption).change();
        } else {
            selectStorageEle.append(
                `<option class="form-select" value="${response.data.storage_type.name_en}" selected>${response.data.storage_type.name_ar}</option>`

            )
        }
        // 
        $(model).find('input[name="name"]').val(response.data.name);
        $(model).find('input[name="cost"]').val(response.data.cost);

        if (model.is('#editModal')) {
            $(model).find(
                `input[name="materialType"][value="${response.data.material_type.name_en}"]`
            ).prop('checked', true);

            var numericPrice = parseFloat(response.data.price.replace(/[^0-9,]/g, '').replace(',', '.'));

            // Set the numeric value in the input
            $(model).find('input[name="price"]').val(numericPrice);

        } else {
            if (response.data.material_type.name_en) {
                let materialType = `
                        <input class="form-check-input material-type" type="radio"
                            value="${response.data.material_type.name_en}" id="view_model_${response.data.material_type.name_ar}"
                            name="materialType" checked>
                        <label class="form-check-label" for="view_model_${response.data.material_type.name_en}">
                            ${response.data.material_type.name_ar}
                        </label>`;

                $(model).find('.view_model_material_type').html(materialType);
            }

            $(model).find('input[name="price"]').val(response.data.price);


        }


        $(model).find('input[name="store_limit_min"]').val(response.data.min_store);
        $(model).find('input[name="store_limit_max"]').val(response.data.max_store);
        $(model).find('input[name="section_limit_min"]').val(response.data.min_section);
        $(model).find('input[name="section_limit_max"]').val(response.data.max_section);

        $(model).find('input[name="serial_nr"]').val(response.data.serial_nr);
        $(model).find('input[name="loss_ratio"]').val(response.data.loss_ratio);

        $(model).find('input[name="expire_date"]').val(response.data.expire_date);

        // Append sections
        let sectionsContent = '';
        if (model.is('#viewModal')) {
            response.data.sections.forEach(function(section) {
                sectionsContent += `
                        <div class='col-md-4 d-flex align-items-center mt-2 '>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                value="${section.name}" 
                                id="section-${section.id}"
                                name="sections" checked>
                                <label class="form-check-label"
                                for="section-${section.id}">
                                    ${section.name}
                                </label>
                            </div>
                        </div>`;
            })
        } else {
            let arr = []
            response.data.sections.forEach(function(section) {
                arr.push(section.name)
            });
            response.sections.forEach(el => {
                let isChecked = arr.includes(el.name) ? 'checked' : '';
                sectionsContent +=
                    `<div class='col-md-4 d-flex align-items-center mt-2 '>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                value="${el.name}"
                                id="edit-model-section-${el.id}"
                                name="sections" ${isChecked}>
                                <label class="form-check-label"
                                for="edit-model-section-${el.id}">
                                ${el.name}
                                </label>
                            </div>
                        </div>`;
            });
        }
        $(model).find('.section_id').html(sectionsContent);
    }

    function bindBranchChangeEvent() {
        $(document).on('change', '#editModal select[name="branch_id"]', function() {
            if (preventChangeEvent) return;
            let slectedMainGroupEle = $(this)

            let sectionCheckboxEle = $('#editModal .section_id')
            let selectedValue = slectedMainGroupEle.val();
            let selectedText = slectedMainGroupEle.find('option:selected').text();
            if (!selectedValue) return;

            const url = '{{ url('stock/material/sections') }}/' + selectedValue + '/filter';
            const initialParams = {
                "branch_id": selectedValue,
            };
            const queryString = $.param(initialParams);


            $.ajax({
                type: "GET",
                url: '{{ url('stock/material/sections') }}/' + selectedValue + '/filter',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        const sections = response.data;
                        let sectionsContent = '';
                        sectionCheckboxEle.html('');
                        if (!sections.length) {
                            sectionsContent =
                                `<div class='col-md-12 d-flex align-items-center mt-2 '>
                                    <p>لاتوجد أقسام متاحة</p>
                            </div>`;
                        }
                        sections.forEach(function(section) {
                            sectionsContent += `
                        <div class='col-md-4 d-flex align-items-center mt-2 '>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                value="${section.name}" 
                                id="edit-model-section-${section.id}"
                                name="sections">
                                <label class="form-check-label"
                                for="edit-model-section-${section.id}">
                                    ${section.name}
                                </label>
                            </div>
                        </div>`;
                        })
                        sectionCheckboxEle.html(sectionsContent);

                    }

                },
                error: handleAjaxError,
            })
        })
    }

    function bindGroupsChangeEvent() {
        $(document).on('change', '#editModal select[name="main_group_id"]', function() {
            if (preventChangeEvent) return;
            let slectedMainGroupEle = $(this)
            let slectedSubMainGroupEle = $('#editModal select[name="sub_group_id"]')
            let selectedValue = slectedMainGroupEle.val();
            let selectedText = slectedMainGroupEle.find('option:selected').text();
            if (!selectedValue) return;

            const url = '{{ url('stock/material/groups') }}/' + selectedValue + '/filter';
            const initialParams = {
                "parent_id": selectedValue,
            };
            const queryString = $.param(initialParams);


            $.ajax({
                type: "GET",
                url: `${url}?${queryString}`,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        slectedSubMainGroupEle.html('');
                        const subGroups = response.data;
                        slectedSubMainGroupEle.prop('disabled', false)

                        if (!subGroups.length) {
                            slectedSubMainGroupEle.append(
                                '<option value="">لاتوجد مجموعات فرعية</option>');
                            return;
                        }
                        subGroups.forEach(group => {
                            const newGroupsOptions =
                                `<option value="${group.id}">${group.name}</option>`
                            slectedSubMainGroupEle.append(newGroupsOptions)
                        });
                    }

                },
                error: handleAjaxError,
            })
        });
    }
</script>
