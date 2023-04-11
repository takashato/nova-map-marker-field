import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('detail-nova-map-marker-field', DetailField)
  app.component('form-nova-map-marker-field', FormField)
})
