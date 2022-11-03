import { createUuid } from "../../uuid";


function editAttributeNumber(search_condtion, name, attribute) {
    const fileInputs = document.querySelectorAll(search_condtion);
    fileInputs.forEach((value, index) => {
        if (isExistBrackets(name)) {
            const delName = name.replace('[]', '');
            value.setAttribute(attribute, `${delName}[${index}]`);
            return;
        }
        value.setAttribute(attribute, `${name}${index}`);
    })
}

function modifyElmsNumber() {
    editAttributeNumber('div[id^="fileItem"]', 'fileItem', 'id');
    editAttributeNumber('input[name^="images"]', 'file', 'id');
    editAttributeNumber('input[name^="images"]', 'images[]', 'name');
    editAttributeNumber('input[name^="image_name"]', 'image_name[]', 'name');
    editAttributeNumber('input[name^="image_name"]', 'image_name', 'id');
    editAttributeNumber('input[name^="pre_image_name"]', 'pre_image_name[]', 'name');
    editAttributeNumber('button[id^="delElm"]', 'delElm', 'id');
    editAttributeNumber('button[id^="fileBtn"]', 'fileBtn', 'id');
}

function isExistBrackets(check_str) {
    const pattern = /\[\]/;
    return (pattern.test(check_str)) ? true : false;
}

function getLengthInputs(name) {
    const inputs = document.querySelectorAll(`input[name^="${name}"]`);
    return inputs.length;
}

function getTemplate() {
    const template = document.querySelector('template');
    const content = template.content;
    const clone = document.importNode(content, true);
    return clone;
}

function isClickDel(id) {
    return (id.indexOf('delElm') === 0) ? true : false;
}

function isClickFileInput(id) {
    return (id.indexOf('fileBtn') === 0) ? true : false;
}

function deltePreImg(parent_elm) {
    const childImgElm = parent_elm.querySelector('img');
    if (childImgElm !== null) {
        childImgElm.remove();
    }
}

function createImgElm(file) {
    const imgUrl = window.URL.createObjectURL(file);
    const img = document.createElement('img');
    img.src = imgUrl;
    return img;
}

function insertImageName(image_name_input_elm) {
    const uuid = createUuid();
    image_name_input_elm.value = uuid;
}


// template追加ボタン発火
const addTemplateBtn = document.getElementById('addTemplate');
addTemplateBtn.addEventListener('click', () => {
    const fileInputField = document.getElementById('fileInput');
    const lengthFileInputs = getLengthInputs('images');
    const templateChildElms = getTemplate();
    templateChildElms.getElementById('fileItem').setAttribute('id', `fileItem${lengthFileInputs}`);
    fileInputField.appendChild(templateChildElms);
    modifyElmsNumber();

})

const fileInputField = document.getElementById('fileInput');
fileInputField.addEventListener('click', (e) => {
    const id = e.target.id;
    const idNum = e.target.id.replace(/[^0-9]/gi, '');
    if (isClickDel(id)) {
        const parentElm = document.getElementById(`fileItem${idNum}`);
        parentElm.remove();
        modifyElmsNumber();
    }

    if (isClickFileInput(id)) {
        const fileInput = document.getElementById(`file${idNum}`);
        fileInput.click();
        fileInput.addEventListener('change', (e) => {
            const parentElm = document.getElementById(`fileItem${idNum}`);
            deltePreImg(parentElm);
            const img = createImgElm(e.target.files[0]);
            parentElm.appendChild(img);

            const imageNameInput = document.getElementById(`image_name${idNum}`);
            insertImageName(imageNameInput);
        })
    }
}, false);

