const root = 'http://localhost:8888/projet4/public/api/'

export const createOrder = async (order) => {
  const options = {
    method: 'POST', 
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(order),
  }

  const response = await fetch(`${root}create_order`, options)
  const result = await response.json()
  return result
}

export const fetchSessionData = async () => {
  try {
    const response = await fetch(`${root}send-session-data`)
    const data = await response.json()

    if (typeof data === 'undefined') return undefined

    return data
  } catch (err) {
    console.log(err)
  }
}

export const setSessionData = async (state) => {
  try {

  } catch(err) {

  }
}

export const syncState = async () => {
  
}