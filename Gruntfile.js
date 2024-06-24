module.exports = function(grunt){

  //config
  grunt.initConfig({
      watch: {
          less: {
              tasks: 'less',
              files: ['./htdocs/style/*.less']
          },
          coffee: {
              tasks: 'coffee uglify',
              files: ['./htdocs/js/*.coffee']
          }
      },
      coffee: {
          compile: {
              files: {
                  './htdocs/js/script.js': ['./htdocs/js/script.coffee']
              }
          }
      },
      less: {
          options: {
              compress: true
          },
          compile: {
              files: {
                  "./htdocs/style/main.css": "./htdocs/style/main.less"
              }
          }
      }
  });

  //load
  grunt.loadNpmTasks("grunt-contrib-less");
  grunt.loadNpmTasks("grunt-contrib-watch");
  grunt.loadNpmTasks("grunt-contrib-coffee");

  //task
  grunt.registerTask("default", ["watch"]);
  grunt.registerTask("compile", ["less", "coffee"]);
  grunt.registerTask("less", ["less"]);
  grunt.registerTask("coffee", ["coffee"]);
}
