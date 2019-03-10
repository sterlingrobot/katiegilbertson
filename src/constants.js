
export const urlgenerate = (str) => {
  return str.trim()
  				.replace(/~[^\w\d]+~/, '-')
			  	.replace(/-$/, '')
			  	.replace(/^-/, '')
			  	.toLowerCase();
}
