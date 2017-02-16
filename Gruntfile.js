module.exports = function(grunt) {
	//Tasks
	grunt.initConfig({
	  	concat: {
		    js: {
		      	src: ['assets/js/bot.js', 'assets/js/player.js', 'assets/js/game.js'],
		      	dest: 'build/js/scripts.js',
		    },
		    css: {
		      	src: ['assets/stylesheets/css/style.css'],
		      	dest: 'build/css/styles.css',
		    }
	  	},
	  	watch: {
	  		js: {
	  			files: ['assets/js/*.js'],
	  			tasks: ['concat:js'],
	  		},
	  		css: {
	  			files: ['assets/stylesheets/scss/**/*.scss'],
	  			tasks: ['sass'],
	  		}
	  	},
	  	sass: {
			dist: {
				files: {
					'assets/stylesheets/css/style.css' : 'assets/stylesheets/scss/main.scss'
				}
			}
		},
		uglify: {
		   	dist: {
		      	options: {
		         	sourceMap: true,
		         	banner: '/*! scripts.js 1.0.0 | Rick Daalhuizen (@rickdaalhuizen90) | MIT Licensed */'
		      	},
		      	files: {
		         	'build/js/scripts.min.js': ['build/js/scripts.js'],
		      	}
		   	}
		},
		cssmin: {
		   	dist: {
		      	options: {
		         	banner: '/*! styles.css 1.0.0 | Rick Daalhuizen (@rickdaalhuizen90) | MIT Licensed */'
		      	},
		      	files: {
		         	'build/css/styles.min.css': ['build/css/styles.css']
		      	}
		  	}
		},
		imagemin: {
		   	dist: {
		      	options: {
		        	optimizationLevel: 5
		      	},
		      	files: [{
		         	expand: true,
		         	cwd: 'assets/images',
		         	src: ['**/*.{png,jpg,gif}'],
		         	dest: 'build/images/'
		      	}]
		   	}
		}
	});

	//Tasks
	grunt.registerTask("default", [ "concat", "watch" ]);
	grunt.registerTask("minimize", [ "uglify", "cssmin", "imagemin" ]);

	// Dependencies
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify'); 
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
};
