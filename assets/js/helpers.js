const getAge = (birthDate) => {
	return (new Date() - birthDate) / 1000 / 3600 / 24 / 365;
}

export const getRate = (birthDate) => {
	const age = getAge(birthDate)
	const normal = {};		// à partir de 12 ans
	normal.price = 1600;
	normal.name = 'Normal';
	const children = {}; 	// de 4 à 12 ans
	children.price = 800; 	
	children.name = 'Enfant';
	const senior = {}; 		// à partir de 60 ans
	senior.price = 1200;
	senior.name = 'Senior';

	return age >= 4 && age <= 12 
	? children
	: age >= 60 ? senior : normal
}

export const isEmailValid = (email) => {
  // Keep it simple. Regex can never be trusted 100% But you can send a validation email. 
  var re = /\S+@\S+\.\S+/;
  return re.test(email);
}

