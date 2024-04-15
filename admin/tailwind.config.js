/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{html,js,ts,tsx}"],
  theme: {
    fontFamily: {
      interblack: ["interblack"],
      interbold: ["interbold"],
      interextra: ["interextra"],
      interlight: ["interlight"],
      intermedium: ["intermedium"],
      interregular: ["interregular"],
      intersemibold: ["intersemibold"],
      interthin: ["interthin"],
    },
    extend: {
      fontSize: {
        xsm: ["13px", "18px"],
      },
      colors: {
        latar: "#F0F0F0",
      },
    },
  },
  plugins: [],
};
