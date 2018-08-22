import React from 'react'


import { Container, Row, Form as BootstrapForm, Col, Input, Label, Button, FormFeedback } from 'reactstrap'
import DayPickerInput from 'react-day-picker/DayPickerInput'
import MomentLocaleUtils, { formatDate, parseDate } from 'react-day-picker/moment'

import 'moment/locale/fr'

const FormOne = props => {
	return (
		<Container>
			<Row>
				<Col md={{ size: 4, order: 2 }} className="mb-4">
					<Cart />
				</Col>
				<Col md={{ size: 8, order: 1 }}>
					<OrderForm 
						handleSubmit={props.handleSubmit} 
						handleInputChange={props.handleInputChange} 
						handleDayChange={props.handleDayChange} 
						{...props} 
					/>
				</Col>
			</Row>
		</Container>
	)
}

const past = {
	before: new Date(),
}

const sundays = {
	daysOfWeek: [0, 2],
}

// 1er mai, 1er novembre et 25 décembre.
const isHoliday = (day) => {
	return day.getDate() === 25 && day.getMonth() === 11 || day.getDate() === 1 && day.getMonth() === 10 || day.getDate() === 1 && day.getMonth() === 4;
}

const container = {
	padding: '.75rem 1.25rem',
	backgroundColor: '#fff',
	border: '1px solid rgba(0,0,0,.125)',
	borderRadius: '.25rem',
}

const OrderForm = (props) => {
	const isEnabled =
	props.emailError === false &&
	props.firstNameError === false &&
	props.lastNameError === false &&
	typeof props.selectedDay === 'object' 
	return (
	<div>
		<h4 className="mb-3">
			Commande
  </h4>

		<BootstrapForm onSubmit={props.handleSubmit} style={container}>
			<Row>
				<Col md="6" className="mb-3">
					<Label for="firstname">Prénom</Label>
					<Input invalid={typeof props.firstNameError === 'string'} type="text" name="firstName" id="firstname" placeholder="Prénom" value={props.firstName} onChange={props.handleInputChange}/>
					<FormFeedback>{props.firstNameError}</FormFeedback>
				</Col>
				<Col md="6" className="mb-3">
					<Label for="lastname">Nom</Label>
					<Input invalid={typeof props.lastNameError === 'string'} type="text" name="lastName" id="lastname" placeholder="Nom" value={props.lastName} onChange={props.handleInputChange}/>
					<FormFeedback>{props.lastNameError}</FormFeedback>
				</Col>
			</Row>
			<div className="mb-3">
				<Label for="email">E-mail</Label>
				<Input invalid={typeof props.emailError === 'string'} type="email" name="email" id="email" placeholder="Email" value={props.email} onChange={props.handleInputChange}/>
				{console.log(props.emailError)}
				<FormFeedback>{props.emailError}</FormFeedback>
				
			</div>
			<div className="mb-3">
				<label>Date</label> <br />
				<DayPickerInput
					className="form-control"
					formatDate={formatDate}
					value={props.selectedDay}
					onDayChange={props.handleDayChange}
					parseDate={parseDate}
					format="LL"
					placeholder={`${formatDate(new Date(), 'LL', 'fr')}`}
					invalid
					dayPickerProps={{
						selectedDays: props.selectedDay,
						locale: 'fr',
						localeUtils: MomentLocaleUtils,
						modifiers: {
							disabled: [isHoliday, past, sundays],
						},
						todayButton: "Aujourd'hui",
					}}
				/>
				<p className="text-danger">{props.isDisabled && 'impossible de résérver cette date'}</p>
			</div>
			<div className="text-center mt-5">
				<Button disabled={!isEnabled} color="link"> Étape suivante &#8594;</Button>
			</div>
		</BootstrapForm>
	</div>
)
};


const Cart = () => (
	<div>
		<h4 className="d-flex justify-content-between align-items-center mb-3">
			<span className="text-muted">Panier</span>
			<span className="badge badge-secondary badge-pill">3</span>
		</h4>

		<ul className="list-group mb-3">
			<li className="list-group-item d-flex justify-content-between lh-condensed">
				<div>
					<h6 className="my-0">Billet Adulte</h6>
					<small className="text-muted">Journée complète</small>
				</div>
				<span className="text-muted">12€</span>
			</li>
			<li className="list-group-item d-flex justify-content-between lh-condensed">
				<div>
					<h6 className="my-0">Billet Adulte</h6>
					<small className="text-muted">Demi-Journée</small>
				</div>
				<span className="text-muted">8€</span>
			</li>
			<li className="list-group-item d-flex justify-content-between lh-condensed">
				<div>
					<h6 className="my-0">Billet Enfant</h6>
					<small className="text-muted">Demi-Journée</small>
				</div>
				<span className="text-muted">5€</span>
			</li>
			<li className="list-group-item d-flex justify-content-between bg-light">
				<div className="text-success">
					<h6 className="my-0">Réduction</h6>
					<small>Document à présenter à l'accueil</small>
				</div>
				<span className="text-success">-5€</span>
			</li>
			<li className="list-group-item d-flex justify-content-between">
				<span>Total (EUR)</span>
				<strong>20€</strong>
			</li>
		</ul>

	</div>
);

export default FormOne