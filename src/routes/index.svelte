<script>
	import { onMount } from 'svelte';

	onMount(async () => {
		const appwrite = new Appwrite();

		appwrite.setEndpoint('https://aw10.matejbaco.eu/v1').setProject('614b43d8d79f1');

		const pricesQuery = await appwrite.database.listDocuments(
			'614b43e17b4b2',
			[],
			31,
			0,
			'createdAt',
			'DESC'
		);

		const labels = [
			'30s',
			'29s',
			'28s',
			'27s',
			'26s',
			'25s',
			'24s',
			'23s',
			'22s',
			'21s',
			'20s',
			'19s',
			'18s',
			'17s',
			'16s',
			'15s',
			'14s',
			'13s',
			'12s',
			'11s',
			'10s',
			'9s',
			'8s',
			'7s',
			'6s',
			'5s',
			'4s',
			'3s',
			'2s',
			'1s',
			'Now'
		];
		const data = {
			labels: labels,
			datasets: [
				{
					label: 'AW Coin price',
					backgroundColor: 'rgb(255, 99, 132)',
					borderColor: 'rgb(255, 99, 132)',
					tension: 0.3,
					fill: true,
					data: pricesQuery.documents.map((doc) => {
						return doc.price;
					})
				}
			]
		};

		const config = {
			hover: {
				mode: 'label'
			},
			responsive: true,
			type: 'line',
			data: data,
			options: {
				animation: {
					duration: 400
				}
			}
		};

		const myChart = new Chart(document.getElementById('myChart'), config);

		appwrite.subscribe('collections.614b43e17b4b2.documents', (event) => {
			if (event.event === 'database.documents.create') {
				const doc = event.payload;

				const currentData = myChart.data.datasets[0].data;
				currentData.shift();
				currentData.push(doc.price);

				myChart.data.datasets[0].data = currentData;
				myChart.update();
			}
		});
	});
</script>

<div class="mx-auto container px-4 py-12">
	<div class="rounded-2xl w-full p-12 bg-white shadow-lg">
		<h1 class="font-bold text-5xl">AW Coin relatime price</h1>
		<p class="text-gray-600 text-lg mt-8">
			You have heard of Bitcoin... But have you herad about Appwrite Coin? 🤯 <br />This is a simple
			project showcasing graph generation using Appwrite realtime 😊<br />We use imaginary price of
			imaginary cryptocurrenty to show off 😎
		</p>

		<p class="text-gray-600 text-lg mt-8">
			The price of Appwrite Coin started at 100 and changes every second.
		</p>
	</div>

	<div class="rounded-2xl w-full p-12 bg-white shadow-lg mt-12">
		<canvas id="myChart" />
	</div>
</div>
