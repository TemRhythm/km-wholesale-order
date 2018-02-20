module.exports = {
    bundle: {
        vendor:{
            scripts: [
                "./node_modules/select2/select2.js",
                "./node_modules/select2/select2_locale_en.js",
                "./node_modules/csv-js/csv.js"
            ],
            styles: [
                "./node_modules/select2/select2.css"
            ],
            options: {
                rev: false
            }
        }
    },
    copy: './node_modules/select2/**/*.{png,svg,gif}'
};