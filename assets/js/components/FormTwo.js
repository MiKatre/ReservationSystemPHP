import React from 'react'
import {Form, FormGroup, Row, Col, Input, Label, CustomInput, Button, UncontrolledTooltip} from 'reactstrap'
import BirthdayPicker from './BirthdayPicker'
import { formatDate } from 'react-day-picker/moment'
import posed, { PoseGroup } from 'react-pose';

import { Radio, AutoComplete, Button as AntButton  } from 'antd';


const RadioButton = Radio.Button;
const RadioGroup = Radio.Group;

const Box = posed.div({
	left: { 
		x: '0%',
		staggerChildren: 100
	},
	right: {x: '10px'}
});

const CalendarAnim = posed.div({
  enter: {scale: 1},
  exit: {scale: 0}
});

class FormTwo extends React.Component {
	constructor(props) {
		super(props)
		this.state = {
			firstName: '',
			lastName: '',
			birthday: undefined,
			isFullDay: undefined,
			discount: false,
			country: undefined,
			showBirthdayPicker: false,
		}
		this.handleInputChange = this.handleInputChange.bind(this)
		this.handleBirthdayFieldClick = this.handleBirthdayFieldClick.bind(this)
		this.handleDayClick = this.handleDayClick.bind(this)
		this.handleSubmit = this.handleSubmit.bind(this)
		this.handleCountryChange = this.handleCountryChange.bind(this)
	}

	componentDidMount(){
		this.props.getTickets()
	}

	handleBirthdayFieldClick(){
		this.setState({
			showBirthdayPicker: !this.state.showBirthdayPicker
		})
	}

	handleDayClick(day, {selected}){
		this.setState({
			birthday: selected ? undefined : day,
			showBirthdayPicker: !this.state.showBirthdayPicker,
		})
	}

	handleInputChange(event){
		const target = event.target
		const name = target.name
		const value = target.type === 'checkbox' ? target.checked : target.value 

		this.setState({
			[name]: value,
		})
	}

	handleCountryChange(value){
		this.setState({country: value})
	}

	wipeState() {
		this.setState({
			firstName: '',
			lastName: '',
			birthday: undefined,
			// isFullDay: undefined,
			discount: false,
		})
	}

	handleSubmit(event){
		event.preventDefault()
		if(this.state.firstName.length !== 0 && this.state.lastName.length !== 0 && typeof this.state.birthday === 'object' && typeof this.state.isFullDay !== 'undefined') {
			const ticket = {}
			ticket.firstName = this.state.firstName
			ticket.lastName = this.state.lastName
			ticket.dateOfBirth = this.state.birthday
			ticket.isFullDay = this.state.isFullDay
			ticket.discount = this.state.discount
			ticket.country = this.state.country
			this.props.handleTicketAdd(ticket)
			this.wipeState()
		}
	}

	render() {
		const date = this.state.birthday ? formatDate(this.state.birthday, 'LL', 'fr'): ''
		const ready = 
		this.state.firstName.length !== 0 
		&& this.state.lastName.length !== 0 
		&& typeof this.state.birthday === 'object' 
		&& typeof this.state.isFullDay !== 'undefined'

		return (
			<div>
				<h4 className="mb-3">
					Billets
			</h4>
				<Form onSubmit={this.handleSubmit} layout="vertical" className="form-container">
					<Row>
						<Col md="6" className="mb-3">
							<Label for="firstname">Prénom</Label>

							<Input type="text" name="firstName" id="firstname" placeholder="Prénom" value={this.state.firstName} onChange={this.handleInputChange}  />

						</Col>
						<Col md="6" className="mb-3">
							<Label for="lastname">Nom</Label>
							<Input type="text" name="lastName" id="lastname" placeholder="Nom" value={this.state.lastName} onChange={this.handleInputChange}  />
						</Col>
					</Row>
					<div className="mb-3">
						<label>Date de naissance</label> <br />
						<div className="d-flex justify-content-start flex-wrap-reverse" >

							<PoseGroup>
									{ this.state.showBirthdayPicker && [
										<CalendarAnim key="1">
											<BirthdayPicker 
												handleDayClick={this.handleDayClick}
												birthday={this.state.birthday}
												className="modal"
											/>
										</CalendarAnim>
									] }
							</PoseGroup>
							
							<Box pose={this.state.showBirthdayPicker ? 'right' : 'left' }>
								<Input 
									type="text" 
									placeholder="Date de naissance" 
									value={date} 
									className="mb-3" 
									onClick={this.handleBirthdayFieldClick} 
									 />

								<label>Pays</label> <br />
								<AutoComplete
									style={{ width: 200 }}
									dataSource={countryListFr}
									placeholder="Pays"
									filterOption={(inputValue, option) => option.props.children.toUpperCase().indexOf(inputValue.toUpperCase()) !== -1}
									value={this.state.country}
									onChange={this.handleCountryChange}

								/>  
								
								<br />

								<Label for="isFullDay" className="mt-3">Type de billet</Label> <i className="fas fa-info-circle" style={{color: '#757575'}}></i>
								<br/>
								<RadioGroup onChange={this.handleInputChange} className="mb-3" name="isFullDay">
									<RadioButton value={true} >journée complète</RadioButton>
									<RadioButton value={false} >demi-journée</RadioButton>
								</RadioGroup>

								<div className="d-flex flex-row">
									<CustomInput type="checkbox" id="discount" label="Je bénéficie d'" checked={this.state.discount} name="discount" onChange={this.handleInputChange} className="mb-3"/> 
									<span style={{color:"blue"}} href="#" id="discountTooltip">une offre réduite</span>
									<UncontrolledTooltip placement="top" target="discountTooltip">
										Il sera nécessaire de présenter sa carte d’étudiant, militaire ou équivalent lors de l’entrée pour prouver qu’on bénéficie bien du tarif réduit.
									</UncontrolledTooltip>
								</div>
									{this.props.counter}
							</Box>
						</div>

					</div>

					<div className="text-center mt-5">
						{/* <Button disabled={!ready} className="default-btn"> Ajouter billet &#43;</Button> */}
						<AntButton type="primary" htmlType="submit" disabled={!ready} > Ajouter billet &#43;</AntButton>
					</div>
				</Form>

					
			</div>
		)
	}
}
export default FormTwo


const countryListEn = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas"
	,"Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands"
	,"Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica"
	,"Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea"
	,"Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana"
	,"Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India"
	,"Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait","Kyrgyz Republic","Laos","Latvia"
	,"Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania"
	,"Mauritius","Mexico","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands","Netherlands Antilles","New Caledonia"
	,"New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal"
	,"Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Satellite","Saudi Arabia","Senegal","Serbia","Seychelles"
	,"Sierra Leone","Singapore","Slovakia","Slovenia","South Africa","South Korea","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","St. Lucia","Sudan"
	,"Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad &amp; Tobago","Tunisia"
	,"Turkey","Turkmenistan","Turks &amp; Caicos","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay"
,"Uzbekistan","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];

const countryListFrJson = {"AF":"Afghanistan","ZA":"Afrique du Sud","AL":"Albanie","DZ":"Alg\u00e9rie","DE":"Allemagne","AD":"Andorre","AO":"Angola","AI":"Anguilla","AQ":"Antarctique","AG":"Antigua-et-Barbuda","SA":"Arabie saoudite","AR":"Argentine","AM":"Arm\u00e9nie","AW":"Aruba","AU":"Australie","AT":"Autriche","AZ":"Azerba\u00efdjan","BS":"Bahamas","BH":"Bahre\u00efn","BD":"Bangladesh","BB":"Barbade","BE":"Belgique","BZ":"Belize","BJ":"B\u00e9nin","BM":"Bermudes","BT":"Bhoutan","BY":"Bi\u00e9lorussie","BO":"Bolivie","BA":"Bosnie-Herz\u00e9govine","BW":"Botswana","BR":"Br\u00e9sil","BN":"Brun\u00e9i Darussalam","BG":"Bulgarie","BF":"Burkina Faso","BI":"Burundi","KH":"Cambodge","CM":"Cameroun","CA":"Canada","CV":"Cap-Vert","EA":"Ceuta et Melilla","CL":"Chili","CN":"Chine","CY":"Chypre","CO":"Colombie","KM":"Comores","CG":"Congo-Brazzaville","CD":"Congo-Kinshasa","KP":"Cor\u00e9e du Nord","KR":"Cor\u00e9e du Sud","CR":"Costa Rica","CI":"C\u00f4te d\u2019Ivoire","HR":"Croatie","CU":"Cuba","CW":"Cura\u00e7ao","DK":"Danemark","DG":"Diego Garcia","DJ":"Djibouti","DM":"Dominique","EG":"\u00c9gypte","SV":"El Salvador","AE":"\u00c9mirats arabes unis","EC":"\u00c9quateur","ER":"\u00c9rythr\u00e9e","ES":"Espagne","EE":"Estonie","VA":"\u00c9tat de la Cit\u00e9 du Vatican","FM":"\u00c9tats f\u00e9d\u00e9r\u00e9s de Micron\u00e9sie","US":"\u00c9tats-Unis","ET":"\u00c9thiopie","EZ":"Eurozone","FJ":"Fidji","FI":"Finlande","FR":"France","GA":"Gabon","GM":"Gambie","GE":"G\u00e9orgie","GS":"G\u00e9orgie du Sud et \u00eeles Sandwich du Sud","GH":"Ghana","GI":"Gibraltar","GR":"Gr\u00e8ce","GD":"Grenade","GL":"Groenland","GP":"Guadeloupe","GU":"Guam","GT":"Guatemala","GG":"Guernesey","GN":"Guin\u00e9e","GQ":"Guin\u00e9e \u00e9quatoriale","GW":"Guin\u00e9e-Bissau","GY":"Guyana","GF":"Guyane fran\u00e7aise","HT":"Ha\u00efti","HN":"Honduras","HU":"Hongrie","CX":"\u00cele Christmas","AC":"\u00cele de l\u2019Ascension","IM":"\u00cele de Man","NF":"\u00cele Norfolk","AX":"\u00celes \u00c5land","KY":"\u00celes Ca\u00efmans","IC":"\u00celes Canaries","CC":"\u00celes Cocos","CK":"\u00celes Cook","FO":"\u00celes F\u00e9ro\u00e9","FK":"\u00celes Malouines","MP":"\u00celes Mariannes du Nord","MH":"\u00celes Marshall","UM":"\u00celes mineures \u00e9loign\u00e9es des \u00c9tats-Unis","PN":"\u00celes Pitcairn","SB":"\u00celes Salomon","TC":"\u00celes Turques-et-Ca\u00efques","VG":"\u00celes Vierges britanniques","VI":"\u00celes Vierges des \u00c9tats-Unis","IN":"Inde","ID":"Indon\u00e9sie","IQ":"Irak","IR":"Iran","IE":"Irlande","IS":"Islande","IL":"Isra\u00ebl","IT":"Italie","JM":"Jama\u00efque","JP":"Japon","JE":"Jersey","JO":"Jordanie","KZ":"Kazakhstan","KE":"Kenya","KG":"Kirghizistan","KI":"Kiribati","XK":"Kosovo","KW":"Kowe\u00eft","RE":"La R\u00e9union","LA":"Laos","LS":"Lesotho","LV":"Lettonie","LB":"Liban","LR":"Lib\u00e9ria","LY":"Libye","LI":"Liechtenstein","LT":"Lituanie","LU":"Luxembourg","MK":"Mac\u00e9doine","MG":"Madagascar","MY":"Malaisie","MW":"Malawi","MV":"Maldives","ML":"Mali","MT":"Malte","MA":"Maroc","MQ":"Martinique","MU":"Maurice","MR":"Mauritanie","YT":"Mayotte","MX":"Mexique","MD":"Moldavie","MC":"Monaco","MN":"Mongolie","ME":"Mont\u00e9n\u00e9gro","MS":"Montserrat","MZ":"Mozambique","MM":"Myanmar (Birmanie)","NA":"Namibie","UN":"Nations Unies","NR":"Nauru","NP":"N\u00e9pal","NI":"Nicaragua","NE":"Niger","NG":"Nig\u00e9ria","NU":"Niue","NO":"Norv\u00e8ge","NC":"Nouvelle-Cal\u00e9donie","NZ":"Nouvelle-Z\u00e9lande","OM":"Oman","UG":"Ouganda","UZ":"Ouzb\u00e9kistan","PK":"Pakistan","PW":"Palaos","PA":"Panama","PG":"Papouasie-Nouvelle-Guin\u00e9e","PY":"Paraguay","NL":"Pays-Bas","BQ":"Pays-Bas carib\u00e9ens","PE":"P\u00e9rou","PH":"Philippines","PL":"Pologne","PF":"Polyn\u00e9sie fran\u00e7aise","PR":"Porto Rico","PT":"Portugal","QA":"Qatar","HK":"R.A.S. chinoise de Hong Kong","MO":"R.A.S. chinoise de Macao","CF":"R\u00e9publique centrafricaine","DO":"R\u00e9publique dominicaine","RO":"Roumanie","GB":"Royaume-Uni","RU":"Russie","RW":"Rwanda","EH":"Sahara occidental","BL":"Saint-Barth\u00e9lemy","KN":"Saint-Christophe-et-Ni\u00e9v\u00e8s","SM":"Saint-Marin","MF":"Saint-Martin","SX":"Saint-Martin (partie n\u00e9erlandaise)","PM":"Saint-Pierre-et-Miquelon","VC":"Saint-Vincent-et-les-Grenadines","SH":"Sainte-H\u00e9l\u00e8ne","LC":"Sainte-Lucie","WS":"Samoa","AS":"Samoa am\u00e9ricaines","ST":"Sao Tom\u00e9-et-Principe","SN":"S\u00e9n\u00e9gal","RS":"Serbie","SC":"Seychelles","SL":"Sierra Leone","SG":"Singapour","SK":"Slovaquie","SI":"Slov\u00e9nie","SO":"Somalie","SD":"Soudan","SS":"Soudan du Sud","LK":"Sri Lanka","SE":"Su\u00e8de","CH":"Suisse","SR":"Suriname","SJ":"Svalbard et Jan Mayen","SZ":"Swaziland","SY":"Syrie","TJ":"Tadjikistan","TW":"Ta\u00efwan","TZ":"Tanzanie","TD":"Tchad","CZ":"Tch\u00e9quie","TF":"Terres australes fran\u00e7aises","IO":"Territoire britannique de l\u2019oc\u00e9an Indien","PS":"Territoires palestiniens","TH":"Tha\u00eflande","TL":"Timor oriental","TG":"Togo","TK":"Tok\u00e9laou","TO":"Tonga","TT":"Trinit\u00e9-et-Tobago","TA":"Tristan da Cunha","TN":"Tunisie","TM":"Turkm\u00e9nistan","TR":"Turquie","TV":"Tuvalu","UA":"Ukraine","UY":"Uruguay","VU":"Vanuatu","VE":"Venezuela","VN":"Vietnam","WF":"Wallis-et-Futuna","YE":"Y\u00e9men","ZM":"Zambie","ZW":"Zimbabwe"}

const countryListFr = ["Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola", "Anguilla", "Antarctique", "Antigua-et-Barbuda", "Arabie saoudite", "Argentine", "Arménie", "Aruba", "Australie", "Autriche", "Azerbaïdjan", "Bahamas", "Bahreïn", "Bangladesh", "Barbade", "Belgique", "Belize", "Bénin", "Bermudes", "Bhoutan", "Biélorussie", "Bolivie", "Bosnie-Herzégovine", "Botswana", "Brésil", "Brunéi Darussalam", "Bulgarie", "Burkina Faso", "Burundi", "Cambodge", "Cameroun", "Canada", "Cap-Vert", "Ceuta et Melilla", "Chili", "Chine", "Chypre", "Colombie", "Comores", "Congo-Brazzaville", "Congo-Kinshasa", "Corée du Nord", "Corée du Sud", "Costa Rica", "Côte d’Ivoire", "Croatie", "Cuba", "Curaçao", "Danemark", "Diego Garcia", "Djibouti", "Dominique", "Égypte", "El Salvador", "Émirats arabes unis", "Équateur", "Érythrée", "Espagne", "Estonie", "État de la Cité du Vatican", "États fédérés de Micronésie", "États-Unis", "Éthiopie", "Eurozone", "Fidji", "Finlande", "France", "Gabon", "Gambie", "Géorgie", "Géorgie du Sud et îles Sandwich du Sud", "Ghana", "Gibraltar", "Grèce", "Grenade", "Groenland", "Guadeloupe", "Guam", "Guatemala", "Guernesey", "Guinée", "Guinée équatoriale", "Guinée-Bissau", "Guyana", "Guyane française", "Haïti", "Honduras", "Hongrie", "Île Christmas", "Île de l’Ascension", "Île de Man", "Île Norfolk", "Îles Åland", "Îles Caïmans", "Îles Canaries", "Îles Cocos", "Îles Cook", "Îles Féroé", "Îles Malouines", "Îles Mariannes du Nord", "Îles Marshall", "Îles mineures éloignées des États-Unis", "Îles Pitcairn", "Îles Salomon", "Îles Turques-et-Caïques", "Îles Vierges britanniques", "Îles Vierges des États-Unis", "Inde", "Indonésie", "Irak", "Iran", "Irlande", "Islande", "Israël", "Italie", "Jamaïque", "Japon", "Jersey", "Jordanie", "Kazakhstan", "Kenya", "Kirghizistan", "Kiribati", "Kosovo", "Koweït", "La Réunion", "Laos", "Lesotho", "Lettonie", "Liban", "Libéria", "Libye", "Liechtenstein", "Lituanie", "Luxembourg", "Macédoine", "Madagascar", "Malaisie", "Malawi", "Maldives", "Mali", "Malte", "Maroc", "Martinique", "Maurice", "Mauritanie", "Mayotte", "Mexique", "Moldavie", "Monaco", "Mongolie", "Monténégro", "Montserrat", "Mozambique", "Myanmar (Birmanie)", "Namibie", "Nations Unies", "Nauru", "Népal", "Nicaragua", "Niger", "Nigéria", "Niue", "Norvège", "Nouvelle-Calédonie", "Nouvelle-Zélande", "Oman", "Ouganda", "Ouzbékistan", "Pakistan", "Palaos", "Panama", "Papouasie-Nouvelle-Guinée", "Paraguay", "Pays-Bas", "Pays-Bas caribéens", "Pérou", "Philippines", "Pologne", "Polynésie française", "Porto Rico", "Portugal", "Qatar", "R.A.S. chinoise de Hong Kong", "R.A.S. chinoise de Macao", "République centrafricaine", "République dominicaine", "Roumanie", "Royaume-Uni", "Russie", "Rwanda", "Sahara occidental", "Saint-Barthélemy", "Saint-Christophe-et-Niévès", "Saint-Marin", "Saint-Martin", "Saint-Martin (partie néerlandaise)", "Saint-Pierre-et-Miquelon", "Saint-Vincent-et-les-Grenadines", "Sainte-Hélène", "Sainte-Lucie", "Samoa", "Samoa américaines", "Sao Tomé-et-Principe", "Sénégal", "Serbie", "Seychelles", "Sierra Leone", "Singapour", "Slovaquie", "Slovénie", "Somalie", "Soudan", "Soudan du Sud", "Sri Lanka", "Suède", "Suisse", "Suriname", "Svalbard et Jan Mayen", "Swaziland", "Syrie", "Tadjikistan", "Taïwan", "Tanzanie", "Tchad", "Tchéquie", "Terres australes françaises", "Territoire britannique de l’océan Indien", "Territoires palestiniens", "Thaïlande", "Timor oriental", "Togo", "Tokélaou", "Tonga", "Trinité-et-Tobago", "Tristan da Cunha", "Tunisie", "Turkménistan", "Turquie", "Tuvalu", "Ukraine", "Uruguay", "Vanuatu", "Venezuela", "Vietnam", "Wallis-et-Futuna", "Yémen", "Zambie", "Zimbabwe"]

// const countryListFr = []

// for(key in countryListFrJson){
// 	countryListFr.push(countryListFrJson[key])
// } 

