function Application() {
    this.controllerURL = 'controller.php';
    this.dropZone = null;
}



Application.prototype.render = function (data) {

    $('#files').html(data.content);

    var self = this;
    $('.delete').click(function () {
        var file = this.getAttribute('data-file');
        self.deleteFile(file);
    });


    if (typeof (data.doublons) !== 'undefined') {
        var doublons = data.doublons;

        for (var i = 0; i < doublons.length; i++) {

            var descriptor = doublons[i];

            var id = descriptor['id'];

            console.debug($('span[data-sentence-id="' + id + '"]').length);

            $('span[data-sentence-id="' + id + '"]').addClass('doublon');
        }


        $('.doublon').hover(function () {
            var sentenceHash = this.getAttribute('data-sentence-hash');
            $('.sentence').addClass('blur');
            $('span[data-sentence-hash="' + sentenceHash + '"]').addClass('hightlight');

        }, function () {
            $('.sentence').removeClass('hightlight');
            $('.sentence').removeClass('blur');
        });
    }
};


Application.prototype.run = function () {
    var dropZone = document.getElementById('filedrop');
    var uploadURL = this.controllerURL;


    this.dropZone = new FileDrop(dropZone, uploadURL, {
        success: function (data) {
            if (data) {
                var data = JSON.parse(data);
            }
            if (!data.error) {
                this.render(data);
            }
        }.bind(this)
    });

    this.load();

};


Application.prototype.load = function () {
    $.ajax({
        type: "GET",
        url: this.controllerURL,
        success: function (data) {
            this.render(data);
        }.bind(this)
    });
};


Application.prototype.deleteFile = function (fileName) {

    $.ajax({
        type: "GET",
        url: this.controllerURL + '?delete=' + fileName,
        success: function (data) {
            this.render(data);
        }.bind(this)
    });
};



