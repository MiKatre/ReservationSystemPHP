import React from 'react'
import ReactDOM from 'react-dom'
import {Container} from 'reactstrap'

import FormContainer from './components/FormContainer'

import 'react-day-picker/lib/style.css'
import '../css/app.css'


class App extends React.Component {
    render(){
        return(
            <Container>
                <FormContainer />
            </Container>
        )
    }
}

ReactDOM.render(<App/>,
    document.getElementById('form'));