var pathCoffee = 'source/coffee/',
    pathJade= 'source/jade/',
    pathSprites= 'source/sprites',
    destJs= '../source/public/static/js/library',
    jsLib= destJs+"/lib.js",
    jades2Html={},
    arrYOSON=[
        pathCoffee + 'yoson/yoson.coffee',
        pathCoffee + 'modules/index.coffee',
        pathCoffee + 'schemas/modules.coffee',
        pathCoffee + 'yoson/appLoad.coffee'
    ];

module.exports = function(grunt){
    grunt.initConfig({
        /*jade:{
            compile: {
                options:{
                    pretty: true,
                    data: grunt.file.readJSON("source/jade/config/data.json")
                },
                files:jades2Html
            }
        },*/
        coffee:{
            options:{
                bare: true
            },
            compile:{
                files:{
                    '../source/public/static/js/library/library.js': arrYOSON
                }
            }
        },
        uglify:{
            options: {
                compress: {
                    drop_console: true
                }
            },
            my_target:{
                files:[{
                    expand: true,
                    cwd: destJs,
                    src: '*.js',
                    dest: destJs
                }]
            }
        },
        exec:{
            styflux: {
                cmd: 'cd source/styflux/ && node init.njs'
            }
        },
        sprite:{
            all:{
                src: pathSprites+'/*.png',
                destImg: '../source/public/static/img/icon-set.png',
                cssFormat: 'stylus',
                destCSS: 'source/styflux/library/styflux/utils/sprites.styl'
            }
        },
        watch: {
            scripts: {
                files: 'source/coffee/**/*.coffee',
                tasks: [ 'scripts' ]
            },
            //jade:{
                //files: 'source/jade/**/*.jade',
                //tasks: [ 'jades' ]
            //},
            styflux:{
                files: 'source/styflux/**/*.styl',
                tasks: [ 'exec' ]
            }
        }
    });

    //loadNpmTasks
    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-contrib-jade');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-coffee');
    grunt.loadNpmTasks('grunt-spritesmith');

    //execute the tasks
    grunt.registerTask('server', ['watch']);
    grunt.registerTask('scripts', 'Compiles the JavaScript files.', ['coffee']);
    grunt.registerTask('jades', 'Compiles the Jade files.', ['jade']);
};
