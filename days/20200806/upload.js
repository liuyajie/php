function upload(options){
    const uploader = WebUploader.create({
        server: options.server,
        pick: '#picker',
        resize: false,
        chunked: true,
        fileNumLimit:1,
        fileSizeLimit:1024*1024*4096,
        fileSingleSizeLimit:1024*1024*4096,
        chunkSize:1024*1024*10,
        timeout:0
    });
    const $list = $('#thelist');
    uploader.on('fileQueued', function (file) {
        $list.html('<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<span class="state">已添加...</span>' +
            '</div>');
    });
    uploader.on('uploadProgress', function (file, percentage) {
        let $li = $('#' + file.id),
            $percent = $li.find('.progress .progress-bar');
        if (!$percent.length) {
            $percent = $('<div class="progress progress-striped active">' +
                '<div class="progress-bar" role="progressbar" style="width: 0%;height:30px;">' +
                '</div>' +
                '</div>').appendTo($li).find('.progress-bar');
        }
        $li.find('p.state').text('上传中...');
        $percent.css('width', percentage * 100 + '%');
    });
    uploader.on('uploadSuccess', function (file) {
        $('#' + file.id).find('p.state').text('已上传...');
    });
    uploader.on('uploadError', function (file) {
        $('#' + file.id).find('p.state').text('上传出错...');
    });
    uploader.on('uploadComplete', function (file) {
        $('#' + file.id).find('.progress').fadeOut();
    });
    $('#ctlBtn').on('click', function () {
        uploader.upload();
    });
    $('#clearBtn').on('click', function () {
        let files = uploader.getFiles();
        console.log(files);
        console.log(files[0]);
        uploader.reset();
        let file1 = uploader.getFiles();
        console.log(file1);
        $('#thelist').html('');
    });
}