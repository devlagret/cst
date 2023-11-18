/** @type {import('tailwindcss').Config} */
export default {
  content: [  
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    container: {
      padding: {
        DEFAULT: '1rem',
        sm: '2rem',
        lg: '4rem',
        xl: '5rem',
        '2xl': '6rem',
      },
    },
    extend: {
      width: {
        '128': '32rem',
        '0.1': '0.1rem',
        '0.25': '0.1rem',
        '0.2': '0.2rem',
        '0.3': '0.3rem',
        '0.4': '0.4rem',
        '0.55': '0.5rem',
        '0.6': '0.6rem',
      }
    },
  },
  plugins: [],
}

