import React, { useState } from 'react';
import ReactDOM from 'react-dom/client';

function Form({states, onChange, onClick}) {
    return (
        <form method="post" action="/">
            <div className="input-group">
                <input onChange={onChange} name="city" type="text" className="form-control" placeholder="City"></input>
                <select onChange={onChange} name="state_id" className="form-select" defaultValue={''}>
                    <option value="" disabled>State</option>
                    {states.length &&
                        states.map((state) => (
                            <option key={state.id} value={state.id}>{state.abbreviation || ''}</option>
                        ))
                    }
                </select>
                <button onClick={onClick} className="btn btn-primary">Search</button>
            </div>
        </form>
    );
}

function Result({result}) {
    let resultSource= <h3>The result came from the API directly</h3>;
    if (result.dbResult) {
        resultSource = (
            <>
                <h3>The result came from the database</h3>
                <p>Data was originally stored at {result.dbResultDate} (UTC)</p>
            </>
        );
    }

    return (
        <div className="container mb-4">
            <h2>{result.title}</h2>
            {resultSource}
            <ol className="list-group list-group-numbered">
                {result.zipCodes.map((item,index) =>
                    <li key={index} className="list-group-item">{item.zipcode}</li>
                )}
            </ol>
        </div>
    )
}

function Error({errors}) {
    return (
        <div className="alert alert-danger mt-2">
            <ul>
                {errors.city && <li>{errors.city[0]}</li>}
                {errors.state_id && <li>The state field is required.</li>}
            </ul>
        </div>
    );
}

function App({states}) {
    const [form, setForm] = useState({});
    const [errorList, setErrorList] = useState({})
    const [result, setResult] = useState({});

    const handleChange = ({ target }) => {
        const {name, value} = target;
        setForm((prev) => ({...prev, [name]: value}));
    };

    const handleSubmit = event => {
        event.preventDefault();

        axios.post('/api/search', {
            city: form.city,
            state_id: form.state_id
        }).then((response) => {
            setErrorList({});
            const result = response.data.result;
            let state = states.find((element) => element.id === result.state_id);
            setResult({
                zipCodes: result.result,
                title: `${result.result.length} zip codes found for ${result.city} + ${state.abbreviation}:`,
                dbResult: response.data.dbResult,
                dbResultDate: result.created_at,
            });
        }).catch(error => {
            if (error.response) {
                setErrorList(error.response.data.errors);
            }
        });
    }

    return (
        <div className="container mt-4">
            <div className="card">
                <div className="card-header text-center font-weight-bold">
                    Search for zip codes by city & state
                </div>
                <div className="card-body">
                    <Form
                        states={states}
                        onChange={(target)=> handleChange(target)}
                        onClick={(event)=> handleSubmit(event)}
                    />

                    {Object.keys(errorList).length !== 0 && <Error errors={errorList}/>}
                </div>
                {Object.keys(result).length !== 0 && <Result result={result}/>}
            </div>
        </div>
    );
}

const element = document.getElementById('zip');
if (element) {
    const states = JSON.parse(element.dataset.states);
    ReactDOM.createRoot(element).render(
        <React.StrictMode>
            <App states={states}/>
        </React.StrictMode>
    )
}
