import React from 'react'

import FormOne from './FormOne'
import FormTwo from './FormTwo'
import FormThree from './FormThree'

export default class FormContainer extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      formOne: {
        completed: false,
      },
      formTwo: {
        completed: false,
      },
      formThree: {
        completed: false,
      },
    }
  }

  render() {
    if (this.state.formThree.completed)    return <ThankYou {...this.state} /> 
    else if (this.state.formTwo.completed) return <FormThree {...this.state} />
    else if (this.state.formOne.completed) return <FormTwo {...this.state} />
    return <FormOne {...this.state.formOne}/> 
  }
} 