export const vData = {
    mounted(el, binding) {
        const { data, columns } = binding.value;

        const thead = document.createElement('thead');
        const tbody = document.createElement('tbody');

        const headerRow = document.createElement('tr');
        columns.forEach(column => {
            const th = document.createElement('th');
            th.textContent = column.label;
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);

        if (Array.isArray(data)) {
            data.forEach(item => {
                const tr = document.createElement('tr');

                columns.forEach(column => {
                    const td = document.createElement('td');
                    const fieldPath = column.field.split('.');
                    let value = item;

                    fieldPath.forEach(field => {
                        try { 
                            value = value[field];
                        }catch {
                            value = '';
                        }
                       
                    });


                    if(fieldPath[0] === 'action'){
                        td.innerHTML = `<a data-id="${item.id}" class="edit cursor-pointer"><i class="fas fa-edit mr-2"></i>Sửa</a>`;
                        tr.appendChild(td);
                    } else{
                        if(column.enums){
                            const enumValue = column.enums.find(enumItem => enumItem.id === value);
                                td.textContent = enumValue ? enumValue.name : '';
                            
                        }else{
                            if(column.field.includes("ngay")){
                                td.textContent = new Date(value).toLocaleDateString();
                            }else{
                                td.textContent = value;
                            }
                        }
                    }
                    tr.appendChild(td);
                });


                tbody.appendChild(tr);
            });
        } else {
            console.warn('v-data expects an array');
        }

        el.innerHTML = '';
        el.appendChild(thead);
        el.appendChild(tbody);
    }
};
