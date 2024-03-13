/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx}"],
  theme: {
    extend: {
      fontFamily: {
        interbold: ["interbold"],
        interlight: ["interlight"],
        intermedium: ["intermedium"],
        interregular: ["interregular"],
        intersemibold: ["intersemibold"],
        interblack: ["interblack"],
        interthin: ["interthin"],
        interextrabold: ["interextrabold"],
      },
    },
  },
  plugins: [],
};
