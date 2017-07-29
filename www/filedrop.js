function FileDrop(node, uploadURL, userOptions)
{
    if(typeof(userOptions)=='undefined') {
        userOptions={};
    }

    this.defaultOptions={
        success: function(data) {
            console.debug(data);
        }
    };

    this.options={};

    for (var attributeName in this.defaultOptions) { this.options[attributeName]=this.defaultOptions[attributeName];}
    for (var attributeName in userOptions) { this.options[attributeName]=userOptions[attributeName];}


    this.uploadURL=uploadURL;

    this.element=node;
    this.element.manager=this;
    this.element.addEventListener("dragenter", this.dragEnter.bind(this), false);
    this.element.addEventListener("dragover", this.dragOver.bind(this), false);
    this.element.addEventListener("dragleave", this.dragLeave.bind(this), false);

    this.element.addEventListener("dragend", this.dragEnd.bind(this), false);

    this.element.addEventListener("drop", this.drop.bind(this), false);
}


FileDrop.prototype.dragEnter=function(e) {
    this.element.className='dragOver';
    e.stopPropagation();
    e.preventDefault();
}

FileDrop.prototype.dragOver=function(e) {
    e.stopPropagation();
    e.preventDefault();
}

FileDrop.prototype.dragLeave=function(e) {
    this.element.className=this.element.className.replace(/\bdragOver\b/, '');
    e.stopPropagation();
    e.preventDefault();
}

FileDrop.prototype.dragEnd=function(e) {
    e.stopPropagation();
    e.preventDefault();
}



FileDrop.prototype.drop=function(e) {
    this.element.className=this.element.className.replace(/\bdragOver\b/, '');

    e.stopPropagation();
    e.preventDefault();
    var dt = e.dataTransfer;
    var files = dt.files;

    for (var i = 0; i < files.length; i++) {
        var file=files[i];
        this.sendFile(file);
    }
}


FileDrop.prototype.sendFile=function(file) {

    var xhr=new XMLHttpRequest();
    xhr.upload.addEventListener("progress", function(e) {
        if (e.lengthComputable) {
            var percentage = Math.round((e.loaded * 100) / e.total);
        }
    }, false);


    xhr.addEventListener("load", function(e) {
        this.options.success(xhr.responseText);
    }.bind(this), true);


    var formData=new FormData();
    formData.append(file.name, file, file.name);


    var reader = new FileReader();
    reader.readAsBinaryString(file);


    reader.onload = function(evt) {
        xhr.send(formData);
    }.bind(this);

    xhr.open("POST", this.uploadURL);
    xhr.overrideMimeType(file.type);
}