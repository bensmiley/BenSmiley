// Generated by CoffeeScript 1.7.1
module.exports = function(grunt) {
  var getRequireJSTasks;
  require('time-grunt')(grunt);
  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),
    lesslint: {
      options: {
        csslint: {
          "known-properties": false
        }
      },
      themeLess: {
        src: ["../css/*.less", "../css/**/*.less"]
      }
    },
    jshint: {
      options: {
        jshintrc: '.jshintrc',
        jshintignore: '.jshintignore'
      },
      themeJS: [],
      SPAJS: []
    },
    coffeelint: {
      options: {
        configFile: 'coffeelint.json'
      },
      themeCoffee: {
        files: {
          src: ["../js/*.coffee", "../js/**/*.coffee"]
        }
      },
      SPACoffee: {
        files: {
          src: ["../SPA/*.coffee", "../SPA/**/*.coffee"]
        }
      }
    },
    phpcs: {
      options: {
        bin: 'C:/xampp/php/phpcs.bat',
        standard: "Wordpress"
      },
      theme: {
        dir: ["../*.php", "../**/*.php"]
      },
      plugins: {
        dir: []
      }
    },
    phpunit: {
      options: {
        bin: "/usr/bin/phpunit",
        bootstrap: "../../../../tests/includes/bootstrap.php",
        colors: true
      },
      theme: {
        classes: {
          dir: ["../tests"]
        }
      },
      plugins: {
        classes: {
          dir: []
        }
      }
    },
    karma: {
      options: {
        runnerPort: 9999,
        browsers: ['Chrome'],
        singleRun: true
      },
      themeJS: {
        configFile: "../js/tests/karma.conf.js"
      },
      SPAJS: {
        configFile: "../SPA/tests/karma.conf.js"
      }
    },
    todo: {
      options: {
        marks: [
          {
            pattern: "TODO",
            color: "#F47605"
          }, {
            pattern: "FIXME",
            color: "red"
          }, {
            pattern: "NOTE",
            color: "blue"
          }
        ]
      },
      lessTODO: {
        src: ["../css/*.less", "../css/**/*.less"]
      },
      phpTODO: {
        src: ["../*.php", "../**/*.php"]
      },
      themeJSTODO: {
        src: ["../js/*.coffee", "../js/**/*.coffee"]
      },
      SPATODO: {
        src: ["../SPA/*.coffee", "../SPA/**/*.coffee"]
      }
    },
    less: {
      production: {
        options: {
          paths: ["../css"],
          cleancss: true,
          compress: true,
          syncImport: true
        },
        files: [
          {
            expand: true,
            cwd: "../css",
            src: ["../css/*.styles.less"],
            dest: "../css",
            ext: ".styles.min.css"
          }
        ]
      }
    },
    clean: {
      prevBuilds: {
        src: ["../css/*.styles.min.css", "../js/*.scripts.min.js", "../SPA/*.spa.min.js"],
        options: {
          force: true
        }
      },
      production: {
        src: ["../production/*"],
        options: {
          force: true
        }
      }
    },
    copyto: {
      production: {
        files: [
          {
            cwd: "../css",
            src: ["*.styles.min.css"],
            dest: "../production/css/"
          }, {
            cwd: "../js",
            src: ["*.scripts.min.js"],
            dest: "../production/js/"
          }, {
            cwd: "../SPA",
            src: ["*.spa.min.js"],
            dest: "../production/spa/"
          }
        ]
      }
    },
    notify: {
      readyToDeploy: {
        options: {
          title: "Code is ready to deploy"
        }
      }
    }
  });
  require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks);
  grunt.registerTask("themeJSOptimize", "Optimize the theme JS files", function() {
    var files, subTasks;
    files = grunt.file.expand("../js/*.scripts.js");
    if (files.length === 0) {
      grunt.log.write("No files to optimize");
      return;
    }
    subTasks = getRequireJSTasks(files, "scripts");
    grunt.config.set('requirejs', subTasks);
    return grunt.task.run("requirejs");
  });
  grunt.registerTask("themeSPAOptimize", "Optimize the SPA JS files", function() {
    var files, subTasks;
    files = grunt.file.expand("../SPA/*.spa.js");
    if (files.length === 0) {
      grunt.log.write("No files to optimize");
      return;
    }
    subTasks = getRequireJSTasks(files, "spa");
    grunt.config.set('requirejs', subTasks);
    return grunt.task.run("requirejs");
  });
  grunt.registerTask("gitCommit", "Commit production files", function() {});
  getRequireJSTasks = function(files, pattern) {
    var optimizedExtension, originalExtension, subTasks;
    subTasks = {};
    originalExtension = "" + pattern + ".js";
    optimizedExtension = "" + pattern + ".min.js";
    files.map(function(file) {
      var config, name;
      config = {
        baseUrl: "../js/",
        mainConfigFile: "../js/require.config.js",
        name: "../js/bower_components/almond/almond.js",
        include: [file],
        out: file.replace(originalExtension, optimizedExtension),
        findNestedDependencies: true,
        optimize: 'none'
      };
      file = file.replace("../js/", "");
      name = file.replace("." + pattern + ".js", "");
      subTasks[name] = {};
      return subTasks[name]["options"] = config;
    });
    return subTasks;
  };
  grunt.registerTask("validate", ["lesslint", "coffeelint", "jshint", "phpcs"]);
  grunt.registerTask("runtests", ["karma", "phpunit"]);
  grunt.registerTask("optimize", ["less", "themeJSOptimize", "themeSPAOptimize"]);
  grunt.registerTask("build", ["themeJSOptimize", "less", "clean:production", "copyto", "clean:prevBuilds"]);
  return grunt.registerTask("deploy", ["validate", "runtests", "optimize", "clean", "copyto", "notify:readyToDeploy"]);
};
