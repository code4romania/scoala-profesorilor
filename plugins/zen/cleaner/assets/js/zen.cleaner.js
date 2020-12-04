var ZenCleaner = new Vue({
    el: '#ZenCleaner',
    delimiters: ['${', '}'],
    data: {
        clean_process: false,
        deleted_size: 0,
        deleted_count: 0,
        dir_count: 0,
        steep: 0,
        success: false,
    },
    filters: {
        sizeFormat: function(fileSizeInBytes) {
            var i = -1;
            var byteUnits = [' kB', ' MB', ' GB', ' TB', ' PB', ' EB', ' ZB', ' YB'];
            do {
                fileSizeInBytes = fileSizeInBytes / 1024;
                i++;
            } while (fileSizeInBytes > 1024);

            return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];
        }
    },
    computed: {
        progress: function () {
            return 'width: '+Math.floor(this.steep*100/this.dir_count)+'%;';
        }
    },
    methods: {
        sync: function (data, url, callback) {
            var $this = this;
            $.ajax({
                url: location.origin + url,
                data:data,
                success: function (data)
                {
                    if(data) {
                        data = JSON.parse(data);
                        callback(data);
                    } else {
                        callback();
                    }
                },
                error: function(x)
                {
                    console.log(x.responseText);
                },
            });
        },
        dirCount: function () {
            if(this.clean_process) return;
            var $this = this;
            this.sync(null, '/zen/cleaner/Clean@dirCount', function (data) {
                $this.dir_count = data.dir_count;
                $this.clean_process = true;
                $this.clean();
            });
        },
        clean: function (steep) {
            if(!steep) {
                this.success = false;
                this.deleted_size = 0;
                this.deleted_count = 0;
                steep = 1
            };
            this.stage = 1;
            var $this = this;
            this.sync({steep:steep}, '/zen/cleaner/Clean@run', function (data) {
                if(!data.over) {
                    $this.deleted_size += parseInt(data.deleted_size);
                    $this.deleted_count += parseInt(data.deleted_count);
                    $this.steep = steep;
                    steep++;
                    $this.clean(steep);
                } else {
                    $this.clean_process = false;
                    $this.success = true;
                }
            });
        },
    }
});