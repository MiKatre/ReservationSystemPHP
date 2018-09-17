import React from 'react'

import { Row, Form as BootstrapForm, Col, Input, Label, Button, FormFeedback } from 'reactstrap'
import {Button as AntButton} from 'antd'

import DayPickerInput from 'react-day-picker/DayPickerInput'
import MomentLocaleUtils, { formatDate, parseDate } from 'react-day-picker/moment'
import 'moment/locale/fr'

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

const FormOne = (props) => {
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

		<BootstrapForm onSubmit={props.handleSubmit} className="form-container">
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
				<div style={{maxWidth: 219}}>
					{props.counter}
				</div>
			</div>
			<AntButton type="primary" htmlType="submit" disabled={!isEnabled} loading={props.loading}> Choisir les billets &#8594; </AntButton>
		</BootstrapForm>
	</div>
)
}

export default FormOne