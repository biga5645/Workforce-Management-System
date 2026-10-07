package com.hostwaypro.bigapp.ui.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.lifecycleScope
import androidx.recyclerview.widget.LinearLayoutManager
import com.hostwaypro.bigapp.databinding.FragmentPendingWorkersBinding
import com.hostwaypro.bigapp.ui.adapters.PendingWorkersAdapter
import com.hostwaypro.bigapp.ui.viewmodel.AdminViewModel
import com.hostwaypro.bigapp.util.Resource
import dagger.hilt.android.AndroidEntryPoint
import kotlinx.coroutines.launch

@AndroidEntryPoint
class PendingWorkersFragment : Fragment() {

    private var _binding: FragmentPendingWorkersBinding? = null
    private val binding get() = _binding!!
    private val viewModel: AdminViewModel by viewModels()
    private lateinit var adapter: PendingWorkersAdapter

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        _binding = FragmentPendingWorkersBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        adapter = PendingWorkersAdapter(
            onApprove = { viewModel.approveWorker(it.uid) },
            onReject = { viewModel.rejectWorker(it.uid) }
        )
        binding.rvPendingWorkers.layoutManager = LinearLayoutManager(requireContext())
        binding.rvPendingWorkers.adapter = adapter

        viewLifecycleOwner.lifecycleScope.launch {
            viewModel.pendingWorkers.collect { resource ->
                when (resource) {
                    is Resource.Loading -> binding.progressBar.visibility = View.VISIBLE
                    is Resource.Success -> {
                        binding.progressBar.visibility = View.GONE
                        adapter.submitList(resource.data)
                    }
                    is Resource.Error -> {
                        binding.progressBar.visibility = View.GONE
                    }
                }
            }
        }
    }

    override fun onDestroyView() {
        super.onDestroyView()
        _binding = null
    }
}
