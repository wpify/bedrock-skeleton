/** @type {import('tailwindcss').Config} */

module.exports = {
	content: [
		'./assets/apps/**/*.js',
		'./assets/apps/**/*.jsx',
		'./web/app/themes/braasi/views/**/*.php',
		'./web/app/themes/braasi/views/**/*.twig',
		'./web/app/themes/braasi/blocks/**/*.php',
		'./web/app/themes/braasi/blocks/**/*.twig',
	],
	theme: {
		fontFamily: {
      'braasi': ["HelveticaNeueLTPro-Bd", "Arial", "sans-serif !important"],
    },
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem',
				sm: '2rem',
				lg: '4rem',
				xl: '5rem',
				'2xl': '6rem',
			},
		},
		extend: {},
	},
	variants: {
		extend: {
			visibility: ["group-hover"],
		},
	 },
	plugins: [
		// require('@tailwindcss/forms'),
		// require('@tailwindcss/typography'),
	],
	corePlugins: {
		preflight: true,
	}
};

