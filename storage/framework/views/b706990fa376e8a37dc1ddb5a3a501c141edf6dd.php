<?php echo $__env->make('includes.stock.Stock_Ajax.public_function', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    $(document).ready(function() {
        let mainGroup = $('#main_group');
        let subGroup = $("#sub_group");
        let materialId = $('#material_id');
        let materialName = $('#material_name');
        let standardCost = $('#standard_cost');
        let price = $('#price');
        let unit = $('#unit');
        let lossRatio = $('#loss_ratio');
        let storeLimitMin = $('#store_limit_min');
        let storeLimitMax = $('#store_limit_max');
        let sectionLimitMin = $('#section_limit_min');
        let sectionLimitMax = $('#section_limit_max');
        let storeMethod = $('#store_method');
        let expire = $('#expire');
        let dailyInventory = $('#daily_inventory');
        let allGroup = $('#all_group');
        let manfu = $('#manfu');
        let packing = $('#packing');
        let saveBtn = $('#save_material');
        let sectionsArray = []

        /*  ======================== Start Get Sub Group ============================== */
        mainGroup.on('change', function() {
            let mainGroup = $('#main_group').val()
            $.ajax({
                url: "<?php echo e(route('get_sub_group')); ?>",
                method: 'post',
                data: {
                    _token,
                    mainGroup,
                },
                success: function(data) {
                    if (data.status == 'true') {
                        let html = '<option selected disabled>اختر المجموعة الفرعية</option>';
                        data.subGroup.forEach((group) => {
                            html += `<option value="${group.id}">${group.name}</option>`
                        });
                        subGroup.html(html)
                        subGroup.select2('open');
                    }
                },
            });
        });
        /*  ======================== End Get Sub Group ============================== */

        /*  ======================== Start Get Code Group ============================== */
        subGroup.on('change', function() {
            let status = $('#pageStatus').attr('status');
            if (status == "new") {
                $.ajax({
                    url: "<?php echo e(route('get_group_code')); ?>",
                    method: 'post',
                    data: {
                        _token,
                        subGroup: subGroup.val(),
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            materialId.val(data.code)
                            materialName.focus()
                        }
                    },
                });
            }
        });
        /*  ======================== End Get Code Group ============================== */

        /*  ======================== Start Search By Name ============================== */
        materialName.on('keyup', function() {
            let query = $(this).val()
            searchDb('search_material_using_name', query, $(this));
        });
        /*  ======================== End Search By Name ============================== */

        /*  ======================== Start Change Branch ============================== */
        $('input[name="branch"]').on('change', function() {
            let branchCheck = $(this);

            if (branchCheck.is(':checked')) {
                sectionsArray.push({
                    id: branchCheck.val(),
                    sections: []
                });
            } else {
                sectionsArray = sectionsArray.filter(section => section.id != branchCheck.val())
            }
            $.ajax({
                url: "<?php echo e(route('get_sections_branch')); ?>",
                method: 'post',
                data: {
                    _token,
                    branch: branchCheck.val()
                },
                success: function(data) {
                    if (data.status == true) {
                        let html = ``
                        data.sections.forEach(section => {
                            html += `<div class="form-check p-0">
                            <input class="form-check-input" type="checkbox" value="${section.id}"
                                        id="section_${section.id}" name="branch_${branchCheck.val()}">
                            <label class="form-check-label" for="section_${section.id}">${section.name}</label>
                        </div>`;
                        });
                        if (branchCheck.is(':checked')) {
                            branchCheck.parents('.form-check').next('.branch-sections').html(html)
                            branchCheck.parents('.form-check').next('.branch-sections').removeClass('d-none')
                        } else {
                            branchCheck.parents('.form-check').next('.branch-sections').html('')
                            branchCheck.parents('.form-check').next('.branch-sections').addClass('d-none')
                        }
                    }
                },
            });
        });
        /*  ======================== End Change Branch ============================== */

        /*  ======================== Start Change Sections ============================== */
        $(document).on('change', 'input[type="checkbox"][name^="branch_"]', function() {
            let branchId = $(this).attr('name').replace('branch_', '');
            let sectionId = $(this).val();

            sectionsArray.forEach(section => {
                if (section.id == branchId) {
                    if ($(this).is(':checked')) {
                        section.sections.push({
                            id: sectionId
                        });
                    } else {
                        section.sections = section.sections.filter(section => section.id != sectionId)
                    }
                }
            });
            console.log(sectionsArray)
        });
        /*  ======================== End Change Sections ============================== */

        /*  ======================== Start Save Material ============================== */
        saveBtn.on('click', function() {
            let requestCheck = false
            sectionsArray.forEach(section => {
                if (section.sections.length != 0) {
                    requestCheck = true
                }
            });
            if (requestCheck) {
                $.ajax({
                    url: "<?php echo e(route('save_material')); ?>",
                    method: 'post',
                    data: {
                        _token,
                        mainGroup: mainGroup.val(),
                        subGroup: subGroup.val(),
                        materialId: materialId.val(),
                        materialName: materialName.val(),
                        standardCost: standardCost.val() || 0,
                        price: price.val() || 0,
                        unit: unit.val(),
                        lossRatio: lossRatio.val() || 0,
                        storeLimitMin: storeLimitMin.val() || 0,
                        storeLimitMax: storeLimitMax.val() || 0,
                        sectionLimitMin: sectionLimitMin.val() || 0,
                        sectionLimitMax: sectionLimitMax.val() || 0,
                        storeMethod: storeMethod.val(),
                        expire: expire.val() || 0,
                        dailyInventory: dailyInventory.is(':checked') ? dailyInventory.val() : 0,
                        allGroup: allGroup.is(':checked') ? allGroup.val() : 0,
                        manfu: manfu.is(':checked') ? manfu.val() : 0,
                        packing: packing.is(':checked') ? packing.val() : 0,
                        section: sectionsArray,
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            materialId.val(data.code)
                            standardCost.val('');
                            price.val('');
                            lossRatio.val('');
                            storeLimitMin.val('');
                            storeLimitMax.val('');
                            sectionLimitMin.val('');
                            sectionLimitMax.val('');
                            expire.val('');
                            dailyInventory.prop('checked', false)
                            allGroup.prop('checked', false);
                            manfu.prop('checked', false);
                            packing.prop('checked', false);
                            materialName.val('').focus();
                            successMsg(data.msg);
                            checkForm();
                        }
                    },
                    error: function(reject) {
                        let response = $.parseJSON(reject.responseText);
                        $.each(response.errors, function(key, val) {
                            errorMsg(val[0]);
                        });
                    }
                });
            } else {
                errorMsg('برجاء تحديد اقل شئ قسم واحد');
            }

        });
        /*  ======================== End Save Material ============================== */

        /*  ======================== Start Get Material ============================== */
        $(document).on('click', '.search-result li', function(e) {
            e.stopPropagation();
            getData('get_material_in_ul', $(this).attr('data-id'), function(data) {
                mainGroup.val(data.main_group).trigger('change');
                setTimeout(() => {
                    subGroup.select2('close').val(data.sub_group).trigger('change');
                }, 500)
                materialId.val(data.code);
                materialName.val(data.name);
                standardCost.val(data.cost);
                price.val(data.price);
                unit.find(`option[value='${data.unit}']`).prop('selected', true);
                lossRatio.val(data.loss);
                storeLimitMin.val(data.min_store);
                storeLimitMax.val(data.max_store);
                sectionLimitMin.val(data.min_section);
                sectionLimitMax.val(data.max_section);
                storeMethod.find(`option[value='${data.storage}']`).prop('selected', true);
                expire.val(data.expire);
                if (data.gard == 1) {
                    dailyInventory.prop('checked', true)
                } else {
                    dailyInventory.prop('checked', false)
                }
                if (data.all_group == 1) {
                    allGroup.prop('checked', true)
                } else {
                    allGroup.prop('checked', false)
                }
                if (data.manfu == 1) {
                    manfu.prop('checked', true)
                } else {
                    manfu.prop('checked', false)
                }
                if (data.packing == 1) {
                    packing.prop('checked', true)
                } else {
                    packing.prop('checked', false)
                }
                let branchArr = []
                data.sections.forEach(section => {
                    if (branchArr.indexOf(section.branch) == -1) {
                        $(`input#branch_${section.branch}[name="branch"]`).prop('checked', true).change();
                    }
                    branchArr.push(section.branch)
                    setTimeout(() => {
                        $(`input#section_${section.section}`).prop('checked', true).change();
                    }, 800)
                });
                $('#save_material').addClass('d-none');
                $('#update_material').removeClass('d-none');
                $('#pageStatus').attr('status', 'update');
                $('.search-result').html('');
                checkForm();
            });
        });
        /*  ======================== Start Get Material ============================== */

        /*  ======================== Start Save Material ============================== */
        $('#update_material').on('click', function() {
            let requestCheck = false
            sectionsArray.forEach(section => {
                if (section.sections.length != 0) {
                    requestCheck = true
                }
            });
            if (requestCheck) {
                $.ajax({
                    url: "<?php echo e(route('update_material')); ?>",
                    method: 'post',
                    data: {
                        _token,
                        mainGroup: mainGroup.val(),
                        subGroup: subGroup.val(),
                        materialId: materialId.val(),
                        materialName: materialName.val(),
                        standardCost: standardCost.val() || 0,
                        price: price.val() || 0,
                        unit: unit.val(),
                        lossRatio: lossRatio.val() || 0,
                        storeLimitMin: storeLimitMin.val() || 0,
                        storeLimitMax: storeLimitMax.val() || 0,
                        sectionLimitMin: sectionLimitMin.val() || 0,
                        sectionLimitMax: sectionLimitMax.val() || 0,
                        storeMethod: storeMethod.val(),
                        expire: expire.val() || 0,
                        dailyInventory: dailyInventory.is(':checked') ? dailyInventory.val() : 0,
                        allGroup: allGroup.is(':checked') ? allGroup.val() : 0,
                        manfu: manfu.is(':checked') ? manfu.val() : 0,
                        packing: packing.is(':checked') ? packing.val() : 0,
                        section: sectionsArray
                    },
                    success: function(data) {
                        if (data.status == 'true') {
                            materialId.val(data.code);
                            subGroup.html('');
                            materialName.val('');
                            standardCost.val('');
                            price.val('');
                            lossRatio.val('');
                            storeLimitMin.val('');
                            storeLimitMax.val('');
                            sectionLimitMin.val('');
                            sectionLimitMax.val('');
                            // unit.find('option:first-child').prop('selected', true);
                            mainGroup.find('option:first-child').prop('selected', true);
                            // storeMethod.find('option:first-child').prop('selected', true);
                            expire.val('');
                            dailyInventory.prop('checked', false)
                            allGroup.prop('checked', false);
                            manfu.prop('checked', false);
                            packing.prop('checked', false);
                            $('input[type="checkbox"][name^="branch"]').each(function() {
                                $(this).prop('checked', false).change()
                            });
                            Swal.fire({
                                icon: 'success',
                                title: data.msg,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $('#update_material').addClass('d-none');
                            saveBtn.removeClass('d-none');
                            $('#pageStatus').attr('status', 'new');
                            checkForm();
                        }
                    },
                    error: function(reject) {
                        let response = $.parseJSON(reject.responseText);
                        $.each(response.errors, function(key, val) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ.....',
                                text: val[0],
                            });
                        });
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ.....',
                    text: 'برجاء تحديد اقل شئ قسم واحد',
                });
            }

        });
    });
    /*  ======================== End Save Material ============================== */
</script><?php /**PATH C:\xampp\htdocs\web_point\resources\views/includes/stock/Stock_Ajax/material.blade.php ENDPATH**/ ?>