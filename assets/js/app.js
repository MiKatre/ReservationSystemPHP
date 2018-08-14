import React from 'react'
import ReactDOM from 'react-dom'

class App extends React.Component {
    render(){
        return(
            <div>
                <p>I am the form </p>
            </div>
        )
    }
}

ReactDOM.render(<App/>,
    document.getElementById('react-form'));